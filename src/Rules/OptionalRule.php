<?php

namespace Processor\Rules;

use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Exceptions\RuleException;

/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 03:35 PM
 */
class OptionalRule extends AbstractRule
{
    /* @var DataProcessor $processor */
    protected $processor;

    public function rule()
    {
        parent::rule();
        if (!isset(self::$data) || empty(self::$data)) {
            return true;
        } else {
            return false;
        }
    }

    public function process()
    {
        if (!$this->rule()) {
            $this->processor->setNameForErrors($this->nameForErrors);
            return $this->processor->process();
        } else {
            return true;
        }
    }

    public function processWithErrors()
    {
        if (!$this->rule()) {
            try {
                $this->processor->setNameForErrors($this->nameForErrors);
                $return = $this->processor->process();
            } catch (FailedProcessingException $e) {
                throw new RuleException($e->getAllErrors());
            }
        } else {
            return true;
        }
    }
}