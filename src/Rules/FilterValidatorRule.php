<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-23
 * Time: 12:34 PM
 */

namespace Processor\Rules;


use Processor\Rules\Abstraction\AbstractRule;

class FilterValidatorRule extends AbstractRule
{
    protected $filter;
    protected $options;

    public function __construct($filter, $options = null)
    {
        parent::__construct();

        $this->filter = $this->typeCheck($filter, 'integer');
        $this->options = $this->typeCheck($options, 'integer');
    }

    public function rule()
    {
        if (($this->data = filter_var($this->data, $this->filter, $this->options)) !== false) {
            return true;
        }
        return false;
    }
}