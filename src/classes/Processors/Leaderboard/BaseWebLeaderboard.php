<?php

namespace Processors\Leaderboard;

use Processors\BaseProcessor;
/**
 * Description of BaseLeaderboard
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
abstract class BaseWebLeaderboard extends BaseProcessor{
    
    protected bool $mustSigned = false;
    protected bool $maintainMode = false;
    
    protected int $offset;
    protected int $length;
    
    const DefaultLength = 12;
    public function __construct() {
        parent::__construct();
        
        $page = filter_input(INPUT_GET, 'page');
        if(empty($page) || $page <= 0) $page = 1;
        
        $length = filter_input(INPUT_GET, 'length');
        if(empty($length)) $length = self::DefaultLength;
        
        $this->offset = ($page - 1) * $length;
        $this->length = $length;
    }
}
