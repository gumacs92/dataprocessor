<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 06:45 PM
 */

namespace Processor\Rules;

class IntTypeRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return is_int(self::$data);
    }
}