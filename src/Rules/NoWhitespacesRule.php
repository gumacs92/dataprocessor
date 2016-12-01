<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 04:02 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class NoWhitespacesRule extends AbstractRule
{
    protected $extraCharacters;

    public function __construct($extraCharacters = '')
    {
        parent::__construct();
        $this->extraCharacters = $this->typeCheck($extraCharacters, 'string');
    }

    public function rule()
    {
        $chars = '/[\s' . $this->extraCharacters . ']/';
        if (preg_match($chars, $this->data))
        {
            return false;
        }
        return true;
    }
}