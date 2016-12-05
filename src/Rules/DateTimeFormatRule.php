<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-29
 * Time: 02:02 AM
 */

namespace Processor\Rules;


use DateTime;
use Processor\Rules\Abstraction\AbstractRule;

class DateTimeFormatRule extends AbstractRule
{
    protected $format;
    protected $locale;

    public function __construct($format, $locale = '')
    {
        parent::__construct();

        $this->format = $this->typeCheck($format, "string");
        $this->locale = $this->typeCheck($locale, "string");
    }

    public function rule()
    {
        if(!($this->data instanceof DateTime)){
            return false;
        }

        /* @var DateTime $this->data */
        if(empty($this->locale)){
            if (($this->data = $this->data->format($this->format)) !== false) {
                return true;
            } else {
                return false;
            }
        }else{
            setlocale(LC_TIME, $this->locale);
            $this->data = utf8_encode(strftime($this->format, $this->data->getTimestamp()));
            return true;
        }
    }

}