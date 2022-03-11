<?php

namespace Processors\Races;

use Consts\Sessions;
use Games\Consts\RaceValue;
use Games\Exceptions\RaceException;
use Games\Users\Holders\UserInfoHolder;
use Games\Users\UserHandler;
use Processors\BaseProcessor;
/**
 * Description of BaseRace
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseRace extends BaseProcessor{
    
    protected bool|null $mustInRace = true;
    protected UserHandler $userHandler;
    protected UserInfoHolder $userInfo;
    
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
}
