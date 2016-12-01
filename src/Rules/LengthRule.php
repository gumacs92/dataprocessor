<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 01:25 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class LengthRule extends AbstractRule
{
    protected $min;
    protected $max;
    protected $inclusive;

    public function __construct($min, $max, $inclusive = true)
    {
        parent::__construct();

        $this->min = $this->typeCheck($min, 'integer');
        $this->max = $this->typeCheck($max, 'integer');
        $this->inclusive = $this->typeCheck($inclusive, 'bool');
    }

    public function rule()
    {
        if(!$this->inclusive){
            $min = $this->min+1;
            $max = $this->max-1;
        }else{
            $min = $this->min;
            $max = $this->max;
        }
        if (is_string($this->data)) {
            if (strlen($this->data) < $min || strlen($this->data) > $max) {
                return false;
            }
            return true;
        } elseif (is_array($this->data)) {
            if (count($this->data) < $min || count($this->data) > $max) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}