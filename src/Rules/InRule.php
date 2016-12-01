<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 03:45 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class InRule extends AbstractRule
{
    protected $list;
    protected $strict;

    public function __construct($list, $strict = false)
    {
        parent::__construct();

        $this->list = $this->typeCheck($list, 'array');
        $this->strict = $this->typeCheck($strict, 'bool');
    }

    public function rule()
    {
        if(array_search($this->data, $this->list, $this->strict) !== false){
            return true;
        }else{
            return false;
        }
    }
}