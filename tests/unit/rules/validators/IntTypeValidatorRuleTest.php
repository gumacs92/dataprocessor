<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 08:41 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;

class IntTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testIntTypeTrue()
    {
        $return = DataProcessor::init()->intType()->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testIntTypeFalse()
    {
        $return = DataProcessor::init()->intType()->verify("123");

        $this->assertEquals(false, $return);
    }

    public function testIntTypeTrueWithError()
    {
        $return = DataProcessor::init()->intType()->verify(10, Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testIntTypeFalseWithError()
    {
        try {
            DataProcessor::init()->intType()->verify(10.1, Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("intType"), $e->getErrors()["intType"]);
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
