<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 12:58 PM
 */

namespace Processor\Rules;

class FloatTypeRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return is_float(self::$data);
    }
}