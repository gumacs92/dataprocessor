<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 01:24 PM
 */

namespace Processor\Rules;


class DigitRule extends AbstractRule
{
    protected $extraCharacters;

    public function rule()
    {
        parent::rule();
        $chars = '/[^0-9'. $this->extraCharacters .']/';
        if (preg_match($chars, self::$data))
        {
            return false;
        }
        return true;
    }
}