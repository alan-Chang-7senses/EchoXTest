<?php

namespace Processors\Notices;

use Accessors\PDOAccessor;
use Consts\EnvVar;
use Consts\ErrorCode;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Versions
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Versions extends BaseProcessor{

    const Disable = 0;
    const Enabled = 1;

    protected bool $mustSigned = false;

    public function Process(): ResultData {

        $version = getenv(EnvVar::AppVersion);

        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->FromTable('ConfigVersions')->WhereEqual('Backend', $version)->WhereEqual('Status', self::Enabled)->FetchAll();

        $versions = [];
        foreach ($rows as $row) {

            $versions[] = [
                'frontend' => $row->Frontend,
                'avatar' => $row->Avatar,
                'featureFlag' => $row->FeatureFlag ?? '',
            ];
        }

        $result = new ResultData(ErrorCode::Success);
        $result->versions = $versions;
        return $result;
    }
}
