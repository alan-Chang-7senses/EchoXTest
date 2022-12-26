<?php

namespace Games\NFTs;

use Consts\ErrorCode;
use Consts\Globals;
use Games\Accessors\AccessorFactory;
use Games\Mails\MailsHandler;
use Generators\DataGenerator;
use Generators\ConfigGenerator;
use Processors\User\Items;
use stdClass;
use Exception;

/**
 * Description of NFTCertificate
 *
 * @author Lin Zheng Fu <sigma@7senses.com>
 */
class NFTItem {

    const LogNotExist = 0;
    const EventType = 'itemRedeemed';
    
    private static function GetItems(int $contentGroupID, int $amount) : array|false {

        if ($amount <= 0) return false;

        $accessor = AccessorFactory::Static();
        $rows = $accessor->FromTable('RewardContent')->WhereEqual('ContentGroupID', $contentGroupID)->FetchAll();
        if ($rows == false) return false;

        $table = [];
        foreach($rows as $row)
        {
            if (isset($table[$row->ItemID])) {                
                $table[$row->ItemID]->Amount += $row->Amount * $amount;
            }
            else {
                $content = new stdClass();
                $content->ItemID = $row->ItemID;
                $content->Amount = $row->Amount * $amount;
                $table[$row->ItemID] = $content;
            }
        }

        $items = [];
        foreach($table as $data)
        {
            $items []= $data;
        }

        return $items;
    }

    private static function SendRewardMail(int $serial, int $userId, int $mailID, array|stdClass $items, stdClass $payload) : void {
        $config = ConfigGenerator::Instance();

        $mailsHandler = new MailsHandler();
        $userMailID = $mailsHandler->AddMail($userId, $mailID, $config->NFTItemMailDay);
        $mailsHandler->AddMailItems($userMailID, $items);

        $content = [];
        $content['userMailID'] =  $userMailID;
        $content['rewards'] =  $items;

        NFTItem::AddLog($serial, $payload, 1, json_encode($content));
    }

    public static function DeployItems(int $serial, int $userId, stdClass $payload) : void {

        $accessor = AccessorFactory::Static();
        $row = $accessor->FromTable('NFTItemsHandleConfig')
                            //->WhereEqual('OutsideCode', $payload->data->planet)
                            ->WhereEqual('NFTItemCode', $payload->data->itemCode)->Fetch();
        if ($row == false)
        {
            NFTItem::AddLog($serial, $payload, 0, "Could not find data, OutsideCode = {$payload->data->planet}, NFTItemCode = {$payload->data->itemCode}");
            throw new Exception ("Unknonw itemCode {$payload->data->itemCode}", ErrorCode::ParamError);
            return;
        }

        $items = NFTItem::GetItems($row->RewardContentGroup, $payload->data->amount);
        if ($items == false)
        {
            NFTItem::AddLog($serial, $payload, 0, "Get Reward Failure, RewardContentGroup = {$row->RewardContentGroup}");
            throw new Exception ("Unknonw RewardContentGroup {$row->RewardContentGroup}", ErrorCode::ParamError);
            return;
        }

        // 派獎方式
        switch($row->HandleType)
        {
            case 0:
                NFTItem::SendRewardMail($serial, $userId, $row->MailID, $items, $payload);
                break;

            default:
                NFTItem::AddLog($serial, $payload, 0, "Unknown HandleType = {$row->HandleType}");
                throw new Exception ("Unknonw HandleType {$row->HandleType}", ErrorCode::ParamError);
                break;
        }
    }

    public static function HandleUserNFTItem(int $userId, string $email) : void {
        // 處理特定玩家未獲得的 NFT 道具
        $accessor = AccessorFactory::Log();

        $rows = $accessor->FromTable('NFTItemLog')
                        ->WhereEqual('Email', $email)
                        ->WhereEqual('IsCompleted', 0)
                        ->FetchAll();
        if ($rows == false) return;

        foreach($rows as $row)
        {
            $payload = new stdClass();
            $payload->type = NFTItem::EventType;
            $payload->data = json_decode ($row->Payload);
            $payload->timestamp = $row->Timestamp;
            NFTItem::DeployItems($row->Serial, $userId, $payload);
        }
    }

    public static function HandleAllNFTItem() : void {
        // 處理所有玩家未獲得的 NFT 道具
        $accessor = AccessorFactory::Log();

        $rows = $accessor->FromTable('NFTItemLog')
                        ->WhereEqual('IsCompleted', 0)
                        ->FetchAll();
        if ($rows == false) return;

        foreach($rows as $row)
        {
            $accessor = AccessorFactory::Main();
            $user = $accessor->SelectExpr('UserID')
                ->FromTable('Users')
                ->WhereEqual('Email', $row->Email)
                ->Fetch();
            if ($user == false) continue;

            $payload = new stdClass();
            $payload->type = NFTItem::EventType;
            $payload->data = json_decode ($row->Payload);
            $payload->timestamp = $row->Timestamp;
            NFTItem::DeployItems($row->Serial, $user->UserID, $payload);
        }
    }

    public static function CheckPayload(stdClass $payload) : void {
        DataGenerator::ExistProperty($payload, 'timestamp');
        DataGenerator::ExistProperty($payload, 'data');

        DataGenerator::ExistProperty($payload->data, 'planet');
        DataGenerator::ExistProperty($payload->data, 'email');
        DataGenerator::ExistProperty($payload->data, 'itemCode');
        DataGenerator::ExistProperty($payload->data, 'amount');
    }

    public static function IsPayloadHandle(stdClass $payload) : int {
        // 先找出同帳號同時間戳的資料，再比對 payload 內容是否一致
        $accessor = AccessorFactory::Log();

        $row = $accessor->FromTable('NFTItemLog')
                        ->WhereEqual('Email', $payload->data->email)
                        ->WhereEqual('Timestamp', $payload->timestamp)
                        ->WhereEqual('Payload', json_encode($payload->data))
                        ->Fetch();
        if ($row == false) return NFTItem::LogNotExist;
        return $row->Serial;
    }

    public static function AddLog(int $serial, stdClass $payload, int $isCompleted, string $message) : void {
        $accessor = AccessorFactory::Log();
        $accessor->FromTable('NFTItemLog');

        if ($serial == NFTItem::LogNotExist)
        {
            $logContent = [
                'Email' => $payload->data->email,
                'Payload' => json_encode($payload->data),
                'IsCompleted' => $isCompleted,
                'Message' => $message,
                'Timestamp' => $payload->timestamp,
                'CreateTime' => $GLOBALS[Globals::TIME_BEGIN],
                'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
            ];
    
            $accessor->Add($logContent);
        }
        else
        {
            $accessor->WhereEqual('Serial', $serial)
                        ->Modify([
                            'IsCompleted' => $isCompleted,
                            'Message' => $message,
                            'UpdateTime' => $GLOBALS[Globals::TIME_BEGIN],
                        ]);
        }        
    }
}
