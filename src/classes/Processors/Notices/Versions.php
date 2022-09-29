<?php

namespace Processors\Notices;

use Consts\ErrorCode;
use Accessors\PDOAccessor;
use Consts\EnvVar;
use Holders\ResultData;
use Processors\BaseProcessor;
/**
 * Description of Versions
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class Versions extends BaseProcessor{
    
    public function Process(): ResultData {
        
        $version = getenv(EnvVar::AppVersion);
        
        $accessor = new PDOAccessor(EnvVar::DBMain);
        $rows = $accessor->FromTable('ConfigVersions')->WhereEqual('Backend', $version)->FetchAll();
        
        $versions = [];
        foreach ($rows as $row) {
            
            $versions[] = [
                'frontend' => $row->Frontend,
                'avatar' => $row->Avatar
            ];
        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->versions = $versions;
        return $result;
    }
}
