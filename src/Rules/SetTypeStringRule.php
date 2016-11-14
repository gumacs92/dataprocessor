<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:55 AM
 */

namespace Processor\Rules;


class SetTypeStringRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return settype(self::$data, 'string');
    }
}