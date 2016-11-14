<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:32 AM
 */

namespace Processor\Rules;


class IntValRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();

        return is_int(filter_var(self::$data, FILTER_VALIDATE_INT));
    }
}