<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 07:51 PM
 */

namespace Processor\Rules\Abstraction;


interface IValidatable
{
    public function rule();

    public function process();

    public function processWithErrors();
}