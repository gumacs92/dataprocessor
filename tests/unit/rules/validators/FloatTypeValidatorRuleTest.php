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
use Processor\Rules\RuleSettings;

class FloatTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testFloatTypeTrue()
    {
        $return = DataProcessor::init()->FloatType()->verify(10.1);

        $this->assertEquals(true, $return);
    }

    public function testFloatTypeFalse()
    {
        $return = DataProcessor::init()->FloatType()->verify(10);

        $this->assertEquals(false, $return);
    }

    public function testFloatTypeTrueWithError()
    {
        $return = DataProcessor::init()->FloatType()->verify(10.1, true);

        $this->assertEquals(true, $return);
    }

    public function testFloatTypeFalseWithError()
    {
        try {
            DataProcessor::init()->FloatType()->verify("123", true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("floatType"), $e->getAllErrors()[0]);
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
