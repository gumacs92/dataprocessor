<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 01:24 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class DigitRule extends AbstractRule
{
    protected $extraCharacters;

    public function __construct($extraCharacters = '')
    {
        parent::__construct();
        $this->extraCharacters = $this->typeCheck($extraCharacters, 'string');
    }

    public function rule()
    {
        $chars = '/[^0-9'. $this->extraCharacters .']/';
        if (preg_match($chars, $this->data))
        {
            return false;
        }
        return true;
    }
}