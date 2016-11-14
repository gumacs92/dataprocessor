<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 01:25 PM
 */

namespace Processor\Rules;


class AlnumRule extends AbstractRule
{
    protected $extraCharacters;

    public function rule()
    {
        parent::rule();
        $chars = '/[^\w' . $this->extraCharacters . ']/';
        if (preg_match($chars, self::$data)) {
            return false;
        }
        return true;
    }
}