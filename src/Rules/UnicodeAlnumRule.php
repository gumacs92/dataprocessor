<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:12 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class UnicodeAlnumRule extends AbstractRule
{
    protected $extraCharacters;

    public function __construct($extraCharacters = '')
    {
        parent::__construct();
        $this->extraCharacters = $this->typeCheck($extraCharacters, "string");
    }

    public function rule()
    {
        $chars = '/(*UTF8)[^\p{L}\p{N}' . $this->extraCharacters . ']/';
        if (preg_match($chars, $this->data)) {
            return false;
        }
        return true;
    }
}