<?php

namespace Processors\Races;

use Consts\Sessions;
use Games\Accessors\AccessorFactory;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Users\Holders\UserInfoHolder;
use Games\Users\UserHandler;
use Processors\BaseProcessor;
use stdClass;
/**
 * Description of BaseRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseRace extends BaseProcessor{
    
    protected bool|null $mustInRace = true;
    protected UserHandler $userHandler;
    protected UserInfoHolder|stdClass $userInfo;
    
    public function __construct() {
        parent::__construct();
        
        $this->userHandler = new UserHandler($_SESSION[Sessions::UserID]);
        $this->userInfo = $this->userHandler->GetInfo();
        
        if($this->mustInRace !== null) $this->CheckInRace ();
    }
    
    private function CheckInRace(){
        
        if($this->mustInRace && $this->userInfo->race == RaceValue::NotInRace) throw new RaceException (RaceException::UserNotInRace);
        else if(!$this->mustInRace && $this->userInfo->race != RaceValue::NotInRace) throw new RaceException (RaceException::UserInRace);
    }

    public function GetRacePlayerID() : int
    {
        $accessor = AccessorFactory::Main();
        $row = $accessor->selectExpr('`RacePlayerID`')->FromTable('RacePlayer')
                ->WhereEqual('UserID',$this->userInfo->id)
                ->WhereEqual('RaceID',$this->userInfo->race)
                ->Fetch();
        if($row === false) throw new RaceException (RaceException::UserNotInRace);
        return $row->RacePlayerID;       
    }
    public function GetCurrentPlayerID() : int
    {
        $accessor = AccessorFactory::Main();
        $row = $accessor->selectExpr('`PlayerID`')->FromTable('RacePlayer')
                ->WhereEqual('UserID',$this->userInfo->id)
                ->WhereEqual('RaceID',$this->userInfo->race)
                ->Fetch();
        if($row === false) throw new RaceException (RaceException::UserNotInRace);
        return $row->PlayerID;       
    }
}
