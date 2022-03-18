<?php
namespace Handlers;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\Sessions;
use SessionHandler;
/**
 * Description of Session
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class SessionToDBHandler extends SessionHandler {
    
    private PDOAccessor $accessor;

    public function open(string $path, string $name): bool {
        
        $this->accessor = new PDOAccessor(getenv(EnvVar::DBLabelMain));
        return parent::open($path, $name);
    }
    
    public function read(string $id): string {
        
        $row = $this->accessor->ClearCondition()->FromTable('Sessions')
                ->SelectExpr('SessionData')
                ->WhereEqual('SessionID', $id)
                ->Fetch();
        return $row === false ? '' : $row->SessionData;
    }
    
    public function write(string $id, string $data): bool {
        
        $items = explode(';', $data);
        
        $userID = 0;
        foreach ($items as $item){
            if(preg_match('/'.Sessions::UserID.'/', $item)){
                $explodeUserID = explode(':', $item);
                $userID = str_replace('"', '', array_pop($explodeUserID));
                break;
            }
        }
        
        $this->accessor->ClearCondition();
        
        return $userID == 0 ? 
                $this->accessor->FromTable('Sessions')->WhereEqual('SessionID', $id)->Delete() : 
                $this->accessor->FromTable('Sessions')->Add([
                    'SessionID' => $id,
                    'SessionExpires' => $_SERVER['REQUEST_TIME'],
                    'SessionData' => $data,
                    'UserID' => $userID
                ], true);
    }
    
    public function destroy(string $id): bool {
        
        return $this->accessor->ClearCondition()->FromTable('Sessions')->WhereEqual('SessionID', $id)->Delete();
    }
    
    public function gc(int $max_lifetime): int|false {
        return $this->accessor->ClearCondition()->FromTable('Sessions')->WhereLess('SessionExpires', $_SERVER['REQUEST_TIME'] - $max_lifetime)->Delete();
    }
}
