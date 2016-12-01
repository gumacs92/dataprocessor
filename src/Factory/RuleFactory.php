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
    /**
     * @param $rulename
     * @return AbstractRule
     */
    public function get($rulename, $arguments){
        $rulefullname = "Processor\\Rules\\" . ucfirst($rulename) . 'Rule';

        if(class_exists($rulefullname)){
            /* @var AbstractRule $rulentity */
            $rulentity = new $rulefullname(...$arguments);
            return $rulentity;
        }

        throw new InvalidRuleException("Fatal error: Call to undefined rule: $rulefullname");
    }

}