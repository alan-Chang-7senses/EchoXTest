<?php

namespace Helpers;

use Consts\ErrorCode;
use Exception;
use Helpers\LogHelper;
use Holders\DBInfo;
use PDO;
use PDOStatement;
use stdClass;
/**
 * Description of PDOHelper
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class PDOHelper {
    
    const PREPARE_DEFAULT = 'none';
    
    private PDO|null $pdo;
    private PDOStatement|null $sth;
    
    private stdClass $holder;
    
    public function __construct(DBInfo $db) {
        
        try{
            
            $this->pdo = new PDO('mysql:dbname='.$db->Name.';host='.$db->Host.';port='.$db->Port , $db->Username , $db->Password , [
//                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = \''.$db->timezone.'\'' ,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            $this->pdo->exec('SET NAMES UTF8');
            
            $this->holder = new stdClass();
            
        } catch (Exception $ex) {

            LogHelper::Extra('PDO_NEW', ['message' => $ex->getMessage(), 'db' => $db]);
            throw new Exception('System connect error', ErrorCode::SystemError, $ex);
        }
    }
    
    public function setTimezone(string $timezone) : void{
        
        if($this->pdo->exec('SET time_zone = \''.$timezone.'\';') === false){
            LogHelper::Extra('PDO_SET_TIMEZONE', ['timezone' => $timezone]);
            throw new Exception ('The database set timezone failure.', ErrorCode::SystemError);
        }
    }
    
    public function prepare(string $statement ,string $name = self::PREPARE_DEFAULT) : void{
        
        if(empty($this->holder->$name) || $name == self::PREPARE_DEFAULT){
            
            try {
                
                $sth = $this->pdo->prepare($statement);
                
            } catch (Exception $exc) {
                
                $this->_prepareLog('PDO_PREPARE_EXCEPTION', $statement, $name);
                throw $exc;
            }
            
            if(!$sth){
                $this->_prepareLog('PDO_PREPARE_FALSE', $statement, $name);
                throw new Exception('The database server cannot successfully prepare the statement', ErrorCode::SQLError);
            }
            
            $this->holder->$name = $sth;
        }
        
        $this->sth = $this->holder->$name;
    }
    
    private function _prepareLog(string $tag, string $statement, string $name) : void{
    
        LogHelper::Extra($tag, [
            'code' => $this->pdo->errorCode(),
            'Info' => $this->pdo->errorInfo(),
            'Statement' => $statement,
            'Prepare Name' => $name,
        ]);
    }
    
    public function closeCursor(string $name) : bool{
        return $this->holder->$name->closeCursor();
    }

    public function execute(array|null $bind = null) : bool{
        
        $result = $this->sth->execute($bind);
        
        if(!$result){
            
            LogHelper::Extra('PDO_EXECUTE', [
                'code' => $this->sth->errorCode(),
                'Info' => $this->sth->errorInfo(),
                'Statement' => $this->sth->queryString,
                'Bind' => $bind
            ]);
            
//            throw new Exception('Executes a prepared statement failure', ErrorCode::SQLError);
        }
        
        return $result;
    }
    
    public function fetch(int $fetchStyle = PDO::FETCH_OBJ) : mixed{
        return $this->sth->fetch($fetchStyle);
    }
    
    public function fetchAll(int $fetchStyle = PDO::FETCH_OBJ) : array|false{
        return $this->sth->fetchAll($fetchStyle);
    }
    
    public function getRowCount() : int{
        return $this->sth->rowCount();
    }
    
    public function getPDOStatement() : PDOStatement{
        return $this->sth;
    }
    
    public function getLastInsertId() : string{
        return $this->pdo->lastInsertId();
    }
    
    public function beginTransaction() : void{
        
        $result = $this->pdo->beginTransaction();
        if($result === false){
            LogHelper::Extra('PDO_BEGIN_TRANSACION', [
                'code' => $this->pdo->errorCode(),
                'Info' => $this->pdo->errorInfo(),
            ]);
            throw new Exception('Initiates a transaction failure', ErrorCode::SystemError);
        }
    }
    
    public function commitTransaction() : void{
        
        $result = $this->pdo->commit();
        if($result === false){
            LogHelper::Extra('PDO_COMMIT_TRANSACION', [
                'code' => $this->pdo->errorCode(),
                'Info' => $this->pdo->errorInfo(),
            ]);
            throw new Exception('Commits a transaction failure', ErrorCode::SystemError);
        }
    }
    
    public function rollbackTransaction() : void{
        
        $result = $this->pdo->rollBack();
        if($result === false){
            LogHelper::Extra('PDO_ROLLBACK_TRANSACION', [
                'code' => $this->pdo->errorCode(),
                'Info' => $this->pdo->errorInfo(),
            ]);
            throw new Exception('Rolls back a transaction failure', ErrorCode::SystemError);
        }
    }

    public function __destruct() {
        
        $this->sth = null;
        $this->pdo = null;
    }
}
