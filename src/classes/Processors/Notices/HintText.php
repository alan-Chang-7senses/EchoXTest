<?php

namespace Processors\Notices;

use Consts\ErrorCode;
use Games\Pools\HintTextPool;
use Helpers\InputHelper;
use Holders\ResultData;
use Processors\BaseProcessor;

/**
 * Description of HintText
 */
class HintText extends BaseProcessor {

    protected bool $mustSigned = false;

    public function Process(): ResultData {
        $hintID = InputHelper::post('hintID');
        $lang = InputHelper::post('lang');

        $hintTexts = HintTextPool::Instance()->{$hintID};
        $result = new ResultData(ErrorCode::Success);
        if (isset($hintTexts->{$lang})) {
            $result->title = $hintTexts->{$lang}->Title;
            $result->content = $hintTexts->{$lang}->Content;
        } else {
            $result->title = "";
            $result->content = "";
        }
        return $result;
    }

}
