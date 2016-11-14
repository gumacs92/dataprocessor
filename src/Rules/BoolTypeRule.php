<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:57 AM
 */

namespace Processor\Rules;


class BoolTypeRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return is_bool(self::$data);
    }
}