<?php

namespace Processors\MainMenu;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Players\PlayerHandler;
use Games\Players\PlayerUtility;
use Games\Pools\ItemInfoPool;
use Games\Races\RaceUtility;
use Games\Users\ItemUtility;
use Holders\ResultData;
use Helpers\InputHelper;
use Processors\BaseProcessor;
use stdClass;

class BattleRecord extends BaseProcessor
{
    private const c_NumRecord = 10;

    public function Process(): ResultData
    {
        $roomIdListData = json_decode(InputHelper::postNotEmpty('roomIdList'));
        $roomIdListData = array_slice($roomIdListData, 0, self::c_NumRecord);

        $accessor = new PDOAccessor(EnvVar::DBMain);

        $roomTypeMap = [];
        $raceIDList = [];
        {
            $raceList = $accessor->FromTable("RaceRooms")->WhereIn("RaceRoomID", $roomIdListData)->SelectExpr("RaceRoomID, Lobby, RaceID")->FetchAll();
            $accessor->ClearAll();
            foreach( $raceList as $room )
            {
                $newItem = new stdClass();
                $newItem->lobby = $room->Lobby;
                $newItem->raceRoomID = $room->RaceRoomID;
                $roomTypeMap[$room->RaceID] = $newItem;
                array_push($raceIDList, $room->RaceID);
            }
        }

        $racePlayerList = $accessor->FromTable("RacePlayer")
            ->WhereEqual("UserID", $_SESSION[Sessions::UserID])
            ->WhereIn("RaceID", $raceIDList)
            ->SelectExpr("RaceID, PlayerID, Ranking, StartTime, FinishTime")->FetchAll();
        $accessor->ClearAll();

        $playerIDList = [];
        foreach( $racePlayerList as $racePlayer ) array_push($playerIDList, $racePlayer->PlayerID);

        $playerDict = [];
        {
            $playerList = $accessor->FromTable("PlayerNFT")->WhereIn("PlayerID", $playerIDList)
                ->SelectExpr("PlayerID, TokenName")->FetchAll();
            $accessor->ClearAll();
            foreach( $playerList as $player )
            {
                $info = (new PlayerHandler($player->PlayerID))->GetInfo();
                $parts = PlayerUtility::PartCodes($info);

                $newItem = new stdClass();
                $idName = PlayerUtility::GetIDName($player->PlayerID);
                $newItem->id = $player->PlayerID;
                $newItem->name = (string)($player->TokenName ?? $idName);
                $newItem->head = $parts->head;
                $newItem->body = $parts->body;
                $newItem->hand = $parts->hand;
                $newItem->leg = $parts->leg;
                $newItem->back = $parts->back;
                $newItem->hat = $parts->hat;
                array_push($playerDict, $newItem);
            }
        }

        $recordList = [];
        foreach( $racePlayerList as $racePlayer )
        {
            $newItem = new stdClass();
            $roomInfo = $roomTypeMap[$racePlayer->RaceID];
            $newItem->roomId = $roomInfo->raceRoomID;
            $newItem->roomType = $roomInfo->lobby;
            $newItem->ranking = $racePlayer->Ranking;
            $newItem->playerID = $racePlayer->PlayerID;
            $newItem->score = 0;
            //$newItem->rewards = [];

            $newItem->useItem = [];
            $itemId = RaceUtility::GetTicketID($roomInfo->lobby);
            $itemInfo = ItemInfoPool::Instance()->{$itemId};
            array_push($newItem->useItem,
                array(
                    "itemId" => $itemId,
                    "itemIcon" => $itemInfo->Icon,
                    "count" => 1));
            $newItem->startTime = $racePlayer->StartTime;
            $newItem->endTime = $racePlayer->FinishTime;
            array_push($recordList, $newItem);
        }

        $result = new ResultData(ErrorCode::Success);
        $result->records = $recordList;
        $result->players = $playerDict;
        return $result;
    }
}

?>