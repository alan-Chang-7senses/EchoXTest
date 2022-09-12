<?php

namespace Games\Accessors;


use Consts\Globals;
use Consts\Sessions;
use Games\Consts\ActionPointValue;
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
            'ResultData' => $GLOBALS[Globals::RESULT_PROCESS_DATA],
            'HttpCode' => http_response_code(),
            'Message' => $GLOBALS[Globals::RESULT_PROCESS_MESSAGE],
            'BeginTime' => $GLOBALS[Globals::TIME_BEGIN],
            'RecordTime' => microtime(true)
        ]);
    }

    public function AddUpgradeLog(int $playerID, ?int $skillID, 
    ?int $skillLevelDelta,?int $coinDelta,?array $bonusType, ?int $expDelta, ?int $rankDelta): void
    {
        $currentTime = $GLOBALS[Globals::TIME_BEGIN];                                
        $this->LogAccessor()->FromTable('Upgrade')->Add([
            'PlayerID' => $playerID,
            'SkillID' => $skillID,
            'SkillLevelDelta' => $skillLevelDelta ?? 0,
            'CoinDelta' => $coinDelta ?? 0,
            'BonusType' => empty($bonusType) ? null : $bonusType[0],
            'ExpDelta' => $expDelta ?? 0,
            'RankDelta' => $rankDelta ?? 0,
            'LogTime' => $currentTime,
        ]);
    }

    public function AddUsePowerLog(int $cause, int $powerBefore, int $powerAfter)
    {
        $logTime = $GLOBALS[Globals::TIME_BEGIN];
        $pveLevel = null;
        if($cause == ActionPointValue::CausePVENormal || $cause == ActionPointValue::CausePVERush)
        {
            //取得PVE關卡
        }
        $this->LogAccessor()->FromTable('PowerLog')->Add([
            'UserID' => $_SESSION[Sessions::UserID],
            'BeforeChange' => $powerBefore,
            'AfterChange' => $powerAfter,
            'Cause' => $cause,
            'PVELevel' => $pveLevel,
            'LogTime' => $logTime,
        ]);
    }
}
