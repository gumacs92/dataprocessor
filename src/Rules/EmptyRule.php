<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-12-03
 * Time: 03:06 AM
 */

namespace Processor\Rules;


use Processor\Rules\Abstraction\AbstractRule;

class EmptyRule extends AbstractRule
{
    public function __construct()
    {
        parent::__construct();
        $this->canBeEmpty =  true;
    }

    public function rule()
    {
        if(empty($this->data) || !isset($this->data)){
            return true;
        }

        return false;
    }
}