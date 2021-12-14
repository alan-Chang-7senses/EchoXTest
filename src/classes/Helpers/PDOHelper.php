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

            LogHelper::Extra('System connect error', ['message' => $ex->getMessage(), 'db' => $db]);
            throw new Exception('System connect error', ErrorCode::SystemError, $ex);
        }
    }
    
    public function setTimezone(string $timezone) : void{
        
        if($this->pdo->exec('SET time_zone = \''.$timezone.'\';') === false)
            throw new Exception ('The database set timezone failure.', ErrorCode::SystemError);
    }
    
    public function prepare(string $statement ,string $name = 'none') : void{
        
        if(empty($this->holder->$name) || $name == 'none'){
            
            $sth = $this->pdo->prepare($statement);
            if(!$sth){
                
                LogHelper::Extra('PDO_PREPARE', [
                    'code' => $this->pdo->errorCode(),
                    'Info' => $this->pdo->errorInfo(),
                    'Statement' => $statement,
                    'Prepare Name' => $name,
                ]);
                
                throw new Exception('The database server cannot successfully prepare the statement', ErrorCode::SQLError);
            }
            
            $this->holder->$name = $sth;
        }
        
        $this->sth = $this->holder->$name;
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
    
    public function fetch(array|null $bind = null , int $fetchStyle = PDO::FETCH_OBJ) : mixed{
        
        $this->execute($bind);
        return $this->sth->fetch($fetchStyle);
    }
    
    public function fetchAll(array|null $bind = null, int $fetchStyle = PDO::FETCH_OBJ) : array|false{
        $this->execute($bind);
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
    
    public function __destruct() {
        
        $this->sth = null;
        $this->pdo = null;
    }
}
