<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 04:41 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class ArrayValRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();

        return is_array(self::$data);
    }

}