<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 04:02 PM
 */

namespace Processor\Rules;


use Processor\Rules\AbstractRule;

class NoWhitespacesRule extends AbstractRule
{
    protected $extraCharacters;

    public function rule()
    {
        parent::rule();
        $chars = '/[\s' . $this->extraCharacters . ']/';
        if (preg_match($chars, self::$data))
        {
            return false;
        }
        return true;
    }
}