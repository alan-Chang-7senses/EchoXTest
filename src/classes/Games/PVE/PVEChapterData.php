<?php

namespace Games\PVE;

use Accessors\MemcacheAccessor;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\PVE\Holders\ChapterInfoHolder;
use stdClass;

class PVEChapterData
{
    private static string $key = 'pveChapter';
    

    public static function GetChapterInfo(int $chapterID) : mixed
    {
        $table = self::GetData();
        return $table->$chapterID;
    }
    /**關卡資料結構：
     *   詳情參考DBStatic：PVEChapter。除此在每個章節中再增加該章所有之關卡清單。
     */
    public static function GetData() : stdClass
    {        
        $mem = MemcacheAccessor::Instance();
        $data = $mem->get(self::$key);
        if($data === false)
        {
            self::SetData();
            $data = $mem->get(self::$key);
        }        
        return json_decode($data);
    }
    private static function SetData()
    {        
        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $rows = $accessor->FromTableJoinUsing('PVEChapter','PVELevel','INNER','ChapterID')->FetchAll();

        $accessor = new PDOAccessor(EnvVar::DBStatic);
        $data = [];
        foreach($rows as $row)
        {
            $holder = new ChapterInfoHolder();
            if(!array_key_exists($row->ChapterID,$data))
            {
                $holder->name = $row->Name;
                $holder->icon = $row->Icon;
                // $holder->isAvalible = $row->Avalible == 1;
                $holder->medalAmountFirst = $row->MedalAmountFirst;
                $holder->rewardIDFirst = $row->RewardIDFirst;
                $holder->medalAmountSecond = $row->MedalAmountSecond;
                $holder->rewardIDSecond = $row->RewardIDSecond;
                $holder->medalAmountThird = $row->MedalAmountThird;
                $holder->rewardIDThrid = $row->RewardIDThrid;
                $holder->preChapters = empty($row->PreChapter) ? null : 
                                    array_map('intval',explode(',', $row->PreChapter));
                $data[$row->ChapterID] = $holder;
            }
            $data[$row->ChapterID]->levels[] = $row->LevelID;
        }
        MemcacheAccessor::Instance()->set(self::$key,json_encode($data));
    }
}