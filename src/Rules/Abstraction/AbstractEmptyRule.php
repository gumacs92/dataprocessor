<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-12-05
 * Time: 07:40 AM
 */

namespace Processor\Rules\Abstraction;


abstract class AbstractEmptyRule extends AbstractRule
{
    public function __construct()
    {
        parent::__construct();
        $this->canBeEmpty =  true;
    }
}