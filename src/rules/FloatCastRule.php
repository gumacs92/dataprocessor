<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 01:36 AM
 */

namespace Processor\Rules;


use Processor\Rules\AbstractRule;

class FloatCastRule extends AbstractRule
{

    public function rule()
    {
        parent::rule();
        return settype(self::$data, 'float');
    }
}