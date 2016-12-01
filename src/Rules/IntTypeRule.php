<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 06:45 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class IntTypeRule extends AbstractRule
{
    public function rule()
    {
        return is_int($this->data);
    }
}