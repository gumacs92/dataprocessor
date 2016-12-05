<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-12-03
 * Time: 03:06 AM
 */

namespace Processor\Rules;


use Processor\Rules\Abstraction\AbstractEmptyRule;

class EmptyRule extends AbstractEmptyRule
{
    public function __construct()
    {
        parent::__construct();
    }

    public function rule()
    {
        if(empty($this->data) || !isset($this->data)){
            return true;
        }

        return false;
    }
}