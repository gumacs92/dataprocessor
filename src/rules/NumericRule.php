<?php

namespace Processor\Rules;

use Processor\Rules\AbstractRule;

/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 01:47 PM
 */
class NumericRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return is_numeric(self::$data);
    }
}