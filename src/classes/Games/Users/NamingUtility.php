<?php
namespace Games\Users;

use Accessors\PDOAccessor;
use PDO;

class NamingUtility
{
    public static function ValidateLength(string $name, int $maxLength) : bool
    {
        return !(strlen($name) <= $maxLength && strlen($name) > 0); 
    }

    public static function HasBandedWord(string $name, string $bandedWords) : bool
    {
        return preg_match($bandedWords,$name);
    }

    public static function IsOnlyEnglishAndNumber(string $name) : bool
    {
        return !preg_match('/[^A-Za-z0-9]/',$name);
    }

    public static function IsNameAlreadyExist(string $name,string $dbName, string $table, string $column)
    {
        $pdo = new PDOAccessor($dbName);
        $alreadyExist = $pdo->FromTable($table)
            ->WhereEqual($column,$name)
            ->Fetch();
        return !$alreadyExist === false;     
    }

    public static function GetBandedWordEnglish() : string
    {
        //TODO：取得禁字庫
        return '/fuck|shit/i';
    }

    public static function GetRandomName(): string
    {
        //TODO：取得自動取名規則
        //測試用
        $adjectives = 
        [
            "beautiful", "clear", "cautious",
            "vivid", "fortunately", "surprisingly",
            "reliable", "simply", "plain","lucky",
            "calm",
        ];
        $nouns = 
        [
            "man" , "woman", "guy", "day",
            "buddy", "kitty", "dinner", "weather",
        ];
        return $adjectives[rand(0,count($adjectives) - 1)].
                $nouns[rand(0,count($nouns) - 1)];        
    }
}