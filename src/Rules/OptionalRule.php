<?php

namespace Processor\Rules;

use Processor\DataProcessor;
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
        $this->processor->setName($this->name);
        $this->canBeEmpty =  true;
    }

    public function rule()
    {
        if (!isset($this->data) || empty($this->data)) {
            return true;
        } else {
            $result = $this->processor->process($this->data, $this->feedback, true);

            if (!$result->isSuccess()) {
                $this->addResultErrorNewLevel($result->getErrors());
            } else {
                $this->data = $result->getData();
            }

            return $result->isSuccess();
        }
    }
}