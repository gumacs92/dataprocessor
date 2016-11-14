<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 03:01 PM
 */

namespace Processor\Rules;


class IfThenElse extends AbstractRule
{
    protected $if;
    protected $then;
    protected $else;

    public function rule()
    {
        parent::rule();
    }

}