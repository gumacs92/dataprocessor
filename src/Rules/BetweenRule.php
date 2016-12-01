<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 09:03 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class BetweenRule extends AbstractRule
{
    protected $min;
    protected $max;
    protected $inclusive;

    public function __construct($min, $max, $inclusive = true)
    {
        parent::__construct();

        $this->min = $this->typeCheck($min, 'numeric');
        $this->max = $this->typeCheck($max, 'numeric');
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
        if ($this->data < $min or $this->data > $max) {
            return false;
        }
        return true;
    }
}