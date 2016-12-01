<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:52 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class SetTypeFloatRule extends AbstractRule
{
    public function rule()
    {
        return settype($this->data, 'float');
    }
}