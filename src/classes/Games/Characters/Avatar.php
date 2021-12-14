<?php
namespace Games\Characters;

use Games\Consts\NFTDNA;
use Consts\ErrorCode;
use Consts\Sessions;
use Games\Exceptions\CharacterException;
use Accessors\PDOAccessor;
use Games\Holders\CharacterPart;
use stdClass;
/**
 * Description of Avatar
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Avatar {
    
    private static function PartCodeByDNA(string $dna) : string{
        return substr($dna, NFTDNA::PartStart, NFTDNA::PartLength);
    }
    
    private static function CharacterPartByNFTData(stdClass $row) : CharacterPart{
        
        $part = new CharacterPart();
        $part->id = $row->CharacterID;
        $part->head = self::PartCodeByDNA($row->HeadDNA);
        $part->body = self::PartCodeByDNA($row->BodyDNA);
        $part->hand = self::PartCodeByDNA($row->HandDNA);
        $part->leg = self::PartCodeByDNA($row->LegDNA);
        $part->back = self::PartCodeByDNA($row->BackDNA);
        $part->hat = self::PartCodeByDNA($row->HatDNA);
        
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
