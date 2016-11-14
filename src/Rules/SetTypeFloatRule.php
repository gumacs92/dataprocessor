<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:52 AM
 */

namespace Processor\Rules;


class SetTypeFloatRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return settype(self::$data, 'float');
    }
}