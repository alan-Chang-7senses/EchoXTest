<?php

namespace Games\Races;

use Consts\EnvVar;
use Consts\Folders;
use DateTime;
use DateTimeZone;
use Exception;
use Games\Consts\RaceVerifyValue;
use Helpers\PathHelper;
use stdClass;

class RaceVerify {

    private static RaceVerify $instance;

    public static function Instance(): RaceVerify {
        if (empty(self::$instance)) {
            self::$instance = new RaceVerify();
            self::$instance->root = PathHelper::getPath(Folders::Log) . "Races" . DIRECTORY_SEPARATOR;
            self::$instance->alllogFile = self::$instance->root . 'AllLog.log';            
        }
        return self::$instance;
    }

    private string $root;
    private string $alllogFile;

//    public function BaseRaceLog(bool $inRace) {
//        $redirectURL = 'NeedInRace:' . ( $inRace ? 'True ' : 'False') . filter_input(INPUT_SERVER, 'REDIRECT_URL');
//        $this->SaveKeys($redirectURL, 'api.log');
//    }
           
    public function AddTestLog(string $message) {
        $this->SaveKeys($message, 'TestMessage.log');        
    }

    public function AddLog(stdClass $raceVerifyInfo) {

        $stage = $raceVerifyInfo->{'verifyStage'};
        $raceVerifyInfo->{'API'} = match ($stage) {
            RaceVerifyValue::VerifyStageReady => 'Ready',
            RaceVerifyValue::VerifyStageStart => 'Start',
            RaceVerifyValue::VerifyStageSkill => 'Skill',
            RaceVerifyValue::VerifyStageOtherSkill => 'OtherSkill',
            RaceVerifyValue::VerifyStagePlayerValue => 'PlayerValue',
            RaceVerifyValue::VerifyStageEnergyAgain => 'EnergyAgain',
            RaceVerifyValue::VerifyStageReachEnd => 'ReachEnd',
            RaceVerifyValue::VerifyStageFinish => 'Finish',
        };
        $raceVerifyInfo->{'Diff'} = $raceVerifyInfo->{'clientDistance'} - $raceVerifyInfo->{'serverDistance'};             
        
        $title = 'Player_' . $raceVerifyInfo->racePlayerID;
        $this->SaveLog($title, $raceVerifyInfo);
    }

    /* private----------------------------------------------------------------------------------------------------- */

    private function SaveKeys(string $key, string $filename) {
        $fullPath = $this->root . $filename;
        $text = self::GetTime(getenv(EnvVar::TimezoneDefault)) . "  " . $key;

        self::WriteLine($text, $fullPath, true);
//        self::WriteLine($text, $this->alllogFile, true);
    }

    private function SaveLog(string $key, array|stdclass|string $data) {
        $fullPath = $this->root . $key . '.log';

        $text = self::GetTime(getenv(EnvVar::TimezoneDefault)) . "  " . $key;
        $content = self::GetText($data);
        if (!empty($content)) {
            $text = $text . PHP_EOL . $content;
        }

        self::WriteLine($text, $fullPath, true);
//        self::WriteLine($text, $this->alllogFile, true);
    }

    /* static----------------------------------------------------------------------------------------------------- */

    private static function GetTime(string $timezone): string {
        $date = new DateTime('now', new DateTimeZone('GMT' . ($timezone >= 0 ? '+' . $timezone : $timezone)));
        return $date->format('Y-m-d H:i:s');
    }

    private static function GetText(array|stdclass|string $data, int $layer = 1): string {

        $text = "";
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (!empty($text)) {
                    $text = $text . PHP_EOL;
                }
                $text = $text . self::TapText($layer) . '[' . $key . ']' . PHP_EOL . self::GetText($value, $layer + 1);
            }
            return $text;
        } else if (is_object($data)) {

            foreach ($data as $key => $value) {

                if (!empty($text)) {
                    $text = $text . PHP_EOL;
                }
                $text = $text . self::TapText($layer) . $key . ' ==> ' . $value;
            }
        } else {
            if (!empty($data)) {
                $text = self::TapText($layer) . $data;
            }
        }

        return $text;
    }

    private static function TapText(int $layer): string {
        $text = '';
        for ($i = 0; $i < $layer; $i++) {
            $text = $text . '   ';
        }
        return $text;
    }

    private static function RemoveDirectory($path) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? self::RemoveDirectory($file) : unlink($file);
        }
        rmdir($path);
    }

    private static function WriteLine(string $value, string $fullPath, bool $append = false) {
        $writeFile = self::OpenFile($fullPath, $append);

        fseek($writeFile, 0);
        fwrite($writeFile, $value . PHP_EOL);
        fclose($writeFile);
    }

    private static function OpenFile(string $fullPath, bool $append = false) {
        $holdDir = dirname($fullPath) . DIRECTORY_SEPARATOR;
        if (!is_dir($holdDir)) {
            $isCreate = mkdir($holdDir);
            if ($isCreate == false) {
                throw new Exception("error", 9999);
            }
        }

        $writeFile = null;
        if (file_exists($fullPath)) {
            if ($append) {
                $writeFile = fopen($fullPath, "a") or die("Unable to open file!");
            } else {
                $writeFile = fopen($fullPath, "w") or die("Unable to open file!");
            }
        } else {
            $writeFile = fopen($fullPath, "w") or die("Unable to open file!");
        }

        return $writeFile;
    }

}
