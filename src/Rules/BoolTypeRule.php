<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:57 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class BoolTypeRule extends AbstractRule
{
    public function rule()
    {
        return is_bool($this->data);
    }
}