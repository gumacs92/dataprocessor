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

        if(is_string(self::$data)){
            $result = strtotime(self::$data);
            if($result !== false && $this->convert === true){
                self::$data = $result;
                return true;
            } elseif($result !== false && $this->convert === false) {
                return true;
            }

            if(empty($this->format)){
                return false;
            }else{
                $return = date_parse_from_format($this->format, self::$data);

                return $return['error_count'] === 0 && $return['warning_count'] === 0;
            }

        }elseif(self::$data instanceof DateTime || self::$data instanceof DateTimeInterface){
            return true;
        }

        return false;
    }
}