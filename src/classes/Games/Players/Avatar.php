<?php
namespace Games\Players;

use Accessors\PDOAccessor;
use Games\Holders\PlayerPart;
use Games\Utilities\PlayerUtility;
use stdClass;
/**
 * Description of Avatar
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Avatar {
    
    private static function PlayerPartByNFTData(stdClass $row) : PlayerPart{
        
        $part = new PlayerPart();
        $part->id = $row->PlayerID;
        $part->head = PlayerUtility::PartCodeByDNA($row->HeadDNA);
        $part->body = PlayerUtility::PartCodeByDNA($row->BodyDNA);
        $part->hand = PlayerUtility::PartCodeByDNA($row->HandDNA);
        $part->leg = PlayerUtility::PartCodeByDNA($row->LegDNA);
        $part->back = PlayerUtility::PartCodeByDNA($row->BackDNA);
        $part->hat = PlayerUtility::PartCodeByDNA($row->HatDNA);
        
        return $part;
    }
    
    public static function PlayersPartByOffset(int $userID, int $offset = 0, int $count = 1) : array{
        
        $rows = (new PDOAccessor('KoaMain'))->FromTableJoinUsing('PlayerNFT', 'PlayerHolder', 'INNER', 'PlayerID')
                ->WhereEqual('UserID', $userID)->OrderBy('PlayerID')->Limit($count, $offset)->FetchAll();
        
        $players = [];
        foreach($rows as $row) $players[] = self::PlayerPartByNFTData ($row);
        
        return $players;
    }
    
    public static function TotalByUser(int $userID) : int{
        $row = (new PDOAccessor('KoaMain'))->SelectExpr('COUNT(*) AS cnt')->FromTable('PlayerHolder')->WhereEqual('UserID', $userID)->Fetch();
        if(empty($row)) return 0;
        return $row->cnt;
    }
}
