<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-24
 * Time: 04:51 PM
 */

namespace Processor\Rules;


use Processor\Rules\Abstraction\AbstractRule;

class PhoneRule extends AbstractRule
{
    public function rule()
    {
        return !empty($this->data) && preg_match('/^([\+]?([\d]{0,3}))?[\(\.\-\s]*(([\d]{1,3})[\)\.\-\s]*)?(([\d]{3,5})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/', $this->data);
    }
}