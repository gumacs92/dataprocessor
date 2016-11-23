<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-23
 * Time: 12:34 PM
 */

namespace Processor\Rules;


use Processor\Rules\Abstraction\AbstractRule;

class FilterValidatorRule extends AbstractRule
{
    protected $filter;
    protected $options;

    public function rule()
    {
        parent::rule();
        if ((self::$data = filter_var(self::$data, $this->filter, $this->options)) !== false) {
            return true;
        }
        return false;
    }
}