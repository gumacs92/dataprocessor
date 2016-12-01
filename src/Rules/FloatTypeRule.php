<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 12:58 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class FloatTypeRule extends AbstractRule
{
    public function rule()
    {
        return is_float($this->data);
    }
}