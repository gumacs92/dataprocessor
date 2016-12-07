<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-12-03
 * Time: 03:09 AM
 */

namespace Processor\Rules;


use Processor\DataProcessor;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\RuleSettings;

class NotRule extends AbstractRule
{
    /* @var DataProcessor $processor */
    protected $processor;

    public function __construct($processor)
    {
        parent::__construct();

        $this->processor = $this->typeCheck($processor, DataProcessor::class);
    }

    public function rule(){

        $this->processor->setName($this->name);
        $return = $this->processor->process($this->data, $this->feedback);

        if($return->isSuccess()){
            $this->addResultErrorNewLevel($this->processor->getMockedErrors(RuleSettings::MODE_NEGATED));

            return false;
        }

        return true;
    }
}