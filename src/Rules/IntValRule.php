<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:32 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class IntValRule extends AbstractRule
{
    public function rule()
    {
        return is_int(filter_var($this->data, FILTER_VALIDATE_INT));
    }
}