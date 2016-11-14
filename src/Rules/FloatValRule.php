<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:56 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;


class FloatValRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();

        return is_float(filter_var(self::$data, FILTER_VALIDATE_FLOAT));
    }
}