<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-29
 * Time: 02:02 AM
 */

namespace Processor\Rules;


use Processor\Rules\Abstraction\AbstractRule;

class DateFormatRule extends AbstractRule
{
    protected $format;

    public function rule()
    {
        parent::rule();

        if((self::$data = date_format(self::$data, $this->format)) !== false){
            return true;
        } else {
            return false;
        }
    }

}