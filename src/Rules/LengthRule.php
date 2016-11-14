<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 01:25 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class LengthRule extends AbstractRule
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
        if (is_string(self::$data)) {
            if (strlen(self::$data) < $min || strlen(self::$data) > $max) {
                return false;
            }
            return true;
        } elseif (is_array(self::$data)) {
            if (sizeof(self::$data) < $min || sizeof(self::$data) > $max) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}