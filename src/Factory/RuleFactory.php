<?php

namespace Processor\Factory;
use Processor\Exceptions\InvalidRuleException;
use Processor\Rules\Abstraction\AbstractRule;

/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 02:12 PM
 */
class RuleFactory
{
    private $errors = [];

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * @param $rulename
     * @return AbstractRule
     */
    public function get($rulename){
        $rulepath = "Processor\\Rules\\" . ucfirst($rulename) . 'Rule';
        return $this->prepareRule($rulename, $rulepath);
    }

    /**
     * @param $rulename
     * @param $rulepath
     * @return AbstractRule
     * @throws InvalidRuleException
     */
    public function prepareRule($rulename, $rulepath){
        if(class_exists($rulepath)){
            /* @var AbstractRule $rulentity */
            $rulentity = new $rulepath();
            $rulentity->setRuleName($rulename);
            return $rulentity;
        }

        $this->errors['invalid_rule_error'] = "Fatal error: Call to undefined rule: $rulepath";
        throw new InvalidRuleException($this->errors['invalid_rule_error']);
    }
}