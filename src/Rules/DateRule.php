<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-29
 * Time: 12:48 AM
 */

namespace Processor\Rules;


use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Processor\Rules\Abstraction\AbstractRule;

class DateRule extends AbstractRule
{
    protected $format;
    protected $convert;

    public function __construct($format = '', $convert = false)
    {
        parent::__construct();

        $this->format = $this->typeCheck($format, "string");
        $this->convert = $this->typeCheck($convert, "bool");
    }

    public function rule()
    {
        if (is_string($this->data)) {
            if (empty($this->format)) {
                $result = strtotime($this->data);
                if ($result !== false && $this->convert === true) {
                    $this->data = $result;
                    $this->data = date("Y-m-d H:i:s", $this->data);
                    $this->data = new DateTime($this->data, new DateTimeZone('UTC'));
                    return true;
                } elseif ($result !== false && $this->convert === false) {
                    return true;
                }

                return false;
            } else {
                $return = date_parse_from_format($this->format, $this->data);

                if ($return['error_count'] === 0 && $return['warning_count'] === 0) {

                    if ($this->convert === true) {
                        $this->data = DateTime::createFromFormat($this->format, $this->data);
                    }

                    return true;
                } else {
                    return false;
                }
            }

        } elseif ($this->data instanceof DateTime || $this->data instanceof DateTimeInterface) {
            return true;
        }

        return false;
    }
}