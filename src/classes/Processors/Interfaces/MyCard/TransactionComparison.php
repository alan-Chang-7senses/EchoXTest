<?php

namespace Processors\Interfaces\MyCard;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Consts\ResposeType;
use Games\Exceptions\StoreException;
use Games\Store\MyCardUtility;
use Generators\DataGenerator;
use Holders\ResultData;
use Processors\BaseProcessor;

/*
 * Description of TransactionComparison
 * 為確保 MyCard 與 CP 廠商之間，雙方交易資料的正確性，由 CP 廠商提供 API 讓 MyCard主動查詢 CP 廠商端交易成功的資料，作雙方交易資料比對的用途。
 */

class TransactionComparison extends BaseProcessor {

    protected bool $mustSigned = false;
    protected int $resposeType = ResposeType::MyCardSDKCallback;

    public function Process(): ResultData {

        if (MyCardUtility::CheckAllowIP() == false) {
            throw new StoreException(StoreException::Error, ['[cause]' => "TL28"]);
        }

        $startDateTime = filter_input(INPUT_POST, 'StartDateTime');
        $endDateTime = filter_input(INPUT_POST, 'EndDateTime');
        $myCardTradeNo = filter_input(INPUT_POST, 'MyCardTradeNo');

        if (!empty($myCardTradeNo)) {
            $logAccessor = new PDOAccessor(EnvVar::DBLog);
            $rowInfos = $logAccessor->FromTable('MyCardPayment')->WhereEqual("MyCardTradeNo", $myCardTradeNo)->FetchAll();
        } else if (!empty($startDateTime) && !empty($endDateTime)) {

            $logAccessor = new PDOAccessor(EnvVar::DBLog);
            $rowInfos = $logAccessor->executeBindFetchAll(sprintf("Select * from MyCardPayment WHERE TradeDateTime BETWEEN %s AND %s;", DataGenerator::TimestampByTimezone($startDateTime, 8), DataGenerator::TimestampByTimezone($endDateTime, 8)), []);
        } else {
            return new ResultData(ErrorCode::ParamError, "param error");
        }

        $result = new ResultData(ErrorCode::Success);
        if (!empty($rowInfos)) {
            $datas = "";
            foreach ($rowInfos as $rowInfo) {
                $oneData = "";

                foreach ($rowInfo as $key => $value) {
                    if (($key == "TradeDateTime") || ($key == "CreateAccountDateTime")) {
                        $value = DataGenerator::TimestrByTimezone($value, 8);
                    }

                    if (empty($oneData)) {
                        $oneData = $value;
                    } else {
                        $oneData = $oneData . "," . $value;
                    }
                }

                $datas = $datas . $oneData . "<BR>";
            }

            $result->response = $datas;
        } else {
            $result->response = "";
        }
        return $result;
    }
}
