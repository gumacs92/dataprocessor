<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:55 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class SetTypeStringRule extends AbstractRule
{
    public function rule()
    {
        return settype($this->data, 'string');
    }
}