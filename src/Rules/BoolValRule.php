<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:57 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class BoolValRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();

        return is_bool(filter_var(self::$data, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }
}