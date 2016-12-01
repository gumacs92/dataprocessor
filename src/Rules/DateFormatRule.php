<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-29
 * Time: 02:02 AM
 */

namespace Processor\Rules;


use Processor\Rules\Abstraction\AbstractRule;

class DateFormatRule extends AbstractRule
{
    protected $format;

    public function __construct($format)
    {
        parent::__construct();

        $this->format = $this->typeCheck($format, "string");
    }

    public function rule()
    {
        if(($this->data = date_format($this->data, $this->format)) !== false){
            return true;
        } else {
            return false;
        }
    }

}