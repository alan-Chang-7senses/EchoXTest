<?php
namespace Games\Characters;

use Accessors\PDOAccessor;
use Games\Exceptions\CharacterException;
use Games\Holders\CharacterPart;
use Games\Utilities\PlayerUtility;
use stdClass;
/**
 * Description of Avatar
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Avatar {
    
    private static function CharacterPartByNFTData(stdClass $row) : CharacterPart{
        
        $part = new CharacterPart();
        $part->id = $row->CharacterID;
        $part->head = PlayerUtility::PartCodeByDNA($row->HeadDNA);
        $part->body = PlayerUtility::PartCodeByDNA($row->BodyDNA);
        $part->hand = PlayerUtility::PartCodeByDNA($row->HandDNA);
        $part->leg = PlayerUtility::PartCodeByDNA($row->LegDNA);
        $part->back = PlayerUtility::PartCodeByDNA($row->BackDNA);
        $part->hat = PlayerUtility::PartCodeByDNA($row->HatDNA);
        
        return $part;
    }

    public static function CharacterPartByID(int $userID, int|null $id = null) : CharacterPart{
        
        $accessor = new PDOAccessor('KoaMain');
        if($id !== null) $accessor->WhereEqual('CharacterID', $id);
        
        $row = $accessor->FromTableJoinUsing('CharacterNFT', 'CharacterHolder', 'INNER', 'CharacterID')
                ->WhereEqual('UserID', $userID)->Limit(1)->Fetch();
        
        if($row === false) throw new CharacterException(CharacterException::NotFound);
        
        return self::CharacterPartByNFTData($row);
    }
    
    public static function CharactersPartByOffset(int $userID, int $offset = 0, int $count = 1) : array{
        
        $rows = (new PDOAccessor('KoaMain'))->FromTableJoinUsing('CharacterNFT', 'CharacterHolder', 'INNER', 'CharacterID')
                ->WhereEqual('UserID', $userID)->OrderBy('CharacterID')->Limit($count, $offset)->FetchAll();
        
        $characters = [];
        foreach($rows as $row) $characters[] = self::CharacterPartByNFTData ($row);
        
        return $characters;
    }
    
    public static function TotalByUser(int $userID) : int{
        $row = (new PDOAccessor('KoaMain'))->SelectExpr('COUNT(*) AS cnt')->FromTable('CharacterHolder')->WhereEqual('UserID', $userID)->Fetch();
        if(empty($row)) return 0;
        return $row->cnt;
    }
}
