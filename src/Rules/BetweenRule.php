<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 09:03 PM
 */

namespace Processor\Rules;


class BetweenRule extends AbstractRule
{
    protected $min;
    protected $max;
    protected $inclusive;

    public function rule()
    {
        parent::rule();
        if(!$this->inclusive){
            $min = $this->min+1;
            $max = $this->max-1;
        }else{
            $min = $this->min;
            $max = $this->max;
        }
        if (self::$data < $min or self::$data > $max) {
            return false;
        }
        return true;
    }
}