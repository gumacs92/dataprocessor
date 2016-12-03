<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 08:40 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;

class FloatTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testFloatTypeTrue()
    {
        $return = DataProcessor::init()->floatType()->process(10.1);

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testFloatTypeFalse()
    {
        $return = DataProcessor::init()->floatType()->process(10);

        $this->assertEquals(false, $return->isSuccess());
    }

    public function testFloatTypeTrueWithError()
    {
        $return = DataProcessor::init()->floatType()->process(10.1, Errors::ALL);

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testFloatTypeFalseWithError()
    {
        try {
            $return = DataProcessor::init()->floatType()->process("123", Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("floatType"), $e->getErrors()["floatType"]);
        } finally {
            $this->assertEquals(false, $return->isSuccess());
        }
    }
}
