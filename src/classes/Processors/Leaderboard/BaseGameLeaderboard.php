<?php

namespace Processors\Leaderboard;

use Helpers\InputHelper;
use Processors\BaseProcessor;
/**
 * Description of BaseGameLeaderboard
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseGameLeaderboard extends BaseProcessor{
    
    protected int $offset;
    protected int $length;
    
    public function __construct() {
        parent::__construct();
        
        $page = InputHelper::post('page');
        if($page <= 0) $page = 1;
        
        $this->length = InputHelper::post('length');
        $this->offset = ($page - 1) * $this->length;
    }
}
