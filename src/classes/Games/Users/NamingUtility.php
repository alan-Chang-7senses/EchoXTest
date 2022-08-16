<?php
namespace Games\Users;

use Accessors\PDOAccessor;
use Consts\EnvVar;

class NamingUtility
{
    const Garbled = '?';
    const FindDirty = 1;
    public static function ValidateLength(string $name, int $maxLength) : bool
    {
        return !(strlen($name) <= $maxLength && strlen($name) > 0); 
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

    public static function HasSymbols(string $checkWord) : bool
    {
        return preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $checkWord) == self::FindDirty;
    }

    public static function HasDirtyWords(string $checkWord, int $bandedWordType)
    {
        $checkWord = strtolower($checkWord);        
        $pdo = new PDOAccessor(EnvVar::DBStatic);
        $rows = $pdo->FromTable('DirtyWord')
            ->WhereEqual('Type',$bandedWordType)
            ->SelectExpr('Word')
            ->FetchAll();
        $bandedWords = [];
        foreach($rows as $row)
        {
            //字變成亂碼
            if($row->Word == '?')continue;
            $bandedWords[] = $row->Word;
        }
        $pattern = '/'.implode('|', $bandedWords).'/';
        return  preg_match($pattern,$checkWord) === self::FindDirty ;
        
    }

}