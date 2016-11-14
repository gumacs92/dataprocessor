<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 12:39 PM
 */

namespace Processor\Rules;


class SetTypeIntRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return settype(self::$data, 'integer');
    }
}