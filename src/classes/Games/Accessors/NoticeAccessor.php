<?php

namespace Games\Accessors;

/**
 * Description of NoticeAccessor
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class NoticeAccessor extends BaseAccessor{
    
    public function rowsMarqueeAssoc(int $lang, int $status) : array {
        return $this->MainAccessor()->FromTable('Marquee')
                ->WhereEqual('Status', $status)->WhereEqual('Lang', $lang)
                ->OrderBy('Sorting')->FetchStyleAssoc()->FetchAll();
    }

    public function rowsRaceMarqueeAssoc(int $lang, array $range, int $status) : array {
        return $this->MainAccessor()->FromTable('Marquee')
                ->WhereIn('Serial', array_values($range))
                ->WhereEqual('Status', $status)->WhereEqual('Lang', $lang)
                ->OrderBy('Sorting')->FetchStyleAssoc()->FetchAll();
    }
}
