<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:12 PM
 */

namespace Processor\Rules;


class UnicodeAlnumRule extends AbstractRule
{
    protected $extraCharacters;

    public function rule()
    {
        parent::rule();

        $chars = '/(*UTF8)[^\p{L}' . $this->extraCharacters . ']/';
        if (preg_match($chars, self::$data)) {
            return false;
        }
        return true;
    }
}