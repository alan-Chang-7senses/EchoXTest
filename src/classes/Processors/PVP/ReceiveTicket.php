<?php

namespace Processors\PVP;

use Consts\Sessions;
use Consts\ErrorCode;
use Games\Consts\ItemValue;
use Holders\ResultData;
use Helpers\InputHelper;
use Games\Races\RaceUtility;
use Processors\BaseProcessor;
use Games\Users\UserBagHandler;
use Games\PVP\QualifyingHandler;
use Games\Exceptions\RaceException;
use stdClass;

class ReceiveTicket extends BaseProcessor
{
    public function Process(): ResultData
    {
        $lobby = InputHelper::post('lobby');

        $qualifyingHandler = new QualifyingHandler();
        $qualifyingHandler->CheckLobbyID($lobby);
        if ($qualifyingHandler->NowSeasonID == -1) {
            throw new RaceException(RaceException::NoSeasonData);
        }

        $userID = $_SESSION[Sessions::UserID];
        $ticket = new stdClass();
        $ticket->ItemID = RaceUtility::GetTicketID($lobby);
        $ticket->Amount = RaceUtility::GetTicketCount($lobby);
        $maxTickets = RaceUtility::GetMaxTickets($lobby);

        $userBagHandler = new UserBagHandler($userID);
        if ($userBagHandler->GetItemAmount($ticket->ItemID) >= $maxTickets) {
            throw new RaceException(RaceException::UserTicketUpperLimit);
        }

        if ($qualifyingHandler->GetRemainTicketTime($userID, $lobby) > 0) {
            throw new RaceException(RaceException::UserTicketNotYet);
        }

        if ($userBagHandler->CheckAddStacklimit($ticket) == false) {
            throw new RaceException(RaceException::UserTicketNotYet);
        }

        if ($qualifyingHandler->SetNextTokenTime($userID, $lobby) == false) {
            throw new RaceException(RaceException::UserTicketError);
        }

        $userBagHandler->AddItems($ticket, ItemValue::CauseRace);
        $result = new ResultData(ErrorCode::Success);
        $result->lobby = $lobby;
        $result->amount = $userBagHandler->GetItemAmount($ticket->ItemID);
        $result->receiveRemainTime = $qualifyingHandler->GetRemainTicketTime($userID, $lobby);
        return $result;
    }


}