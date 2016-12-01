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

class StringTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testStringTypeTrue()
    {
        $return = DataProcessor::init()->stringType()->verify("123");

        $this->assertEquals(true, $return);
    }

    public function testStringTypeFalse()
    {
        $return = DataProcessor::init()->stringType()->verify(10);

        $this->assertEquals(false, $return);
    }

    public function testStringTypeTrueWithError()
    {
        $return = DataProcessor::init()->stringType()->verify("123", Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testStringTypeFalseWithError()
    {
        try {
            DataProcessor::init()->stringType()->verify(10, Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("stringType"), $e->getErrors()["stringType"]);
        } finally {
            $this->assertEquals(false, $return);
        }
    }


}
