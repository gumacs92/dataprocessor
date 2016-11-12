<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 01:25 PM
 */

namespace Processor\Rules;


use Processor\Rules\AbstractRule;

class AlnumRule extends AbstractRule
{
    protected $extraCharacters;

    public function rule()
    {
        parent::rule();
        $chars = '/[^a-zA-Z0-9' . $this->extraCharacters . ']/';
        if (preg_match($chars, self::$data)) {
            return false;
        }
        return true;
    }
}