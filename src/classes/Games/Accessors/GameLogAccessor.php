<?php

namespace Games\Accessors;

use Consts\Globals;
use Consts\Sessions;
use Generators\DataGenerator;
/**
 * Description of GameLogAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class GameLogAccessor extends BaseAccessor{
    
    public function AddBaseProcess() : void{
        
        $this->LogAccessor()->FromTable('BaseProcess')->Add([
            'UserID' => $_SESSION[Sessions::UserID] ?? 0,
            'UserIP' => DataGenerator::UserIP(),
            'RedirectURL' => $GLOBALS[Globals::REDIRECT_URL],
            'Content' => json_encode($_POST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'Result' => (int)$GLOBALS[Globals::RESULT_PROCESS],
            'Message' => $GLOBALS[Globals::RESULT_PROCESS_MESSAGE],
            'BeginTime' => $GLOBALS[Globals::TIME_BEGIN],
            'RecordTime' => microtime(true)
        ]);
    }
}
