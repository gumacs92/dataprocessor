<?php

namespace Processor\Rules;

use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\AbstractRule;

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

    public function __construct($processor)
    {
        parent::__construct();
        $this->processor = $this->typeCheck($processor, DataProcessor::class);
    }

    public function rule()
    {
        if (!isset($this->data) || empty($this->data)) {
            return true;
        } else {
            try {
                $this->processor->setName($this->name);
                $return = $this->processor->verify($this->data, $this->feedback);

                if ($return) {
                    $this->data = $this->processor->getData();
                } else {
                    $this->addReturnErrors($this->processor->getMockedErrors());
                }

            } catch (FailedProcessingException $e) {
                $this->addReturnErrors($e->getErrors());
            }
            if ($return) {
                return true;
            }
            return false;
        }
    }
}