<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 03:45 PM
 */

namespace Processor\Rules;


class InRule extends AbstractRule
{
    protected $list;
    protected $strict;

    public function rule()
    {
        parent::rule();

        if(array_search(self::$data, $this->list, $this->strict) !== false){
            return true;
        }else{
            return false;
        }
    }
}