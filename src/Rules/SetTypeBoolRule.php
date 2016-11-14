<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 12:53 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class SetTypeBoolRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return settype(self::$data, 'bool');
    }
}