<?php

namespace Processors\PVP;

use Consts\Sessions;
use Consts\ErrorCode;

use Holders\ResultData;
use Helpers\InputHelper;
use Games\Races\RaceUtility;
use Processors\BaseProcessor;
use Games\Users\UserBagHandler;
use Games\PVP\QualifyingHandler;
use Games\Exceptions\RaceException;

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
        $ticketId = RaceUtility::GetTicketID($lobby);
        $ticketCount = RaceUtility::GetTicketCount($lobby);        
        $maxTickets =RaceUtility::GetMaxTickets($lobby);
        if ($qualifyingHandler->FindItemAmount($userID, $ticketId) >= $maxTickets) {
            throw new RaceException(RaceException::UserTicketUpperLimit);
        }

        if ($qualifyingHandler->GetRemainTicketTime($userID, $lobby) > 0) {
            throw new RaceException(RaceException::UserTicketNotYet);
        }

        if ($qualifyingHandler->SetNextTokenTime($userID, $lobby) == false) {
            throw new RaceException(RaceException::UserTicketError);
        }

        $userBagHandler  = new UserBagHandler($userID);
        $userBagHandler->AddItem($ticketId, $ticketCount);
        $result = new ResultData(ErrorCode::Success);
        $result->ticket = $qualifyingHandler->GetTicketInfo($userID, $lobby);

        return $result;
    }


}