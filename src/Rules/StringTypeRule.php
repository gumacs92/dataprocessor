<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 02:10 AM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class StringTypeRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();
        return is_string(self::$data);
    }
}