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
use Processor\Rules\Abstraction\AbstractRule;

class DateRule extends AbstractRule
{
    protected $format;
    protected $convert;

    public function rule()
    {
        parent::rule();

        if (is_string(self::$data)) {
            if (empty($this->format)) {
                $result = strtotime(self::$data);
                if ($result !== false && $this->convert === true) {
                    self::$data = $result;
                    //TODO buggy what if datetime failes...
                    self::$data = new DateTime(self::$data);
                    return true;
                } elseif ($result !== false && $this->convert === false) {
                    return true;
                }

                return false;
            } else {
                $return = date_parse_from_format($this->format, self::$data);

                if ($return['error_count'] === 0 && $return['warning_count'] === 0) {

                    if ($this->convert === true) {
                        self::$data = DateTime::createFromFormat($this->format, self::$data);
                    }

                    return true;
                } else {
                    return false;
                }
            }

        } elseif (self::$data instanceof DateTime || self::$data instanceof DateTimeInterface) {
            return true;
        }

        return false;
    }
}