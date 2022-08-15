<?php
namespace Games\Users;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Games\Consts\BandedWordValue;
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

    public static function HasSymbols(string $checkWord) : bool
    {
        return preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $checkWord) == 1;
    }

    // public static function HasBandedWords(string $checkWord, int $bandedWordType) : bool
    // {
    //     $pdo = new PDOAccessor(EnvVar::DBStatic);
    //     $rows = $pdo->FromTable('BandedWord')
    //         ->WhereEqual('Type',BandedWordValue::BandWordName)
    //         ->SelectExpr('Word')
    //         ->FetchAll();
    //     $bandedWords = [];
    //     foreach($rows as $row)
    //     {
    //         $bandedWords[] = $row->Word;
    //     }
    //     $a = ['a','bdgf','gfd','g'];
    //     $t = '/'.implode('|', $a).'/';
    //     $
    //     return preg_match('/'.implode('|', $bandedWords).'/',$checkWord) == 1;
    // }

}