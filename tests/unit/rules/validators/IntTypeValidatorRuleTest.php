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
use Processor\Rules\RuleSettings;

class IntTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testIntTypeTrue()
    {
        $return = DataProcessor::init()->IntType()->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testIntTypeFalse()
    {
        $return = DataProcessor::init()->IntType()->verify("123");

        $this->assertEquals(false, $return);
    }

    public function testIntTypeTrueWithError()
    {
        $return = DataProcessor::init()->IntType()->verify(10, true);

        $this->assertEquals(true, $return);
    }

    public function testIntTypeFalseWithError()
    {
        try {
            DataProcessor::init()->IntType()->verify(10.1, true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("intType"), $e->getAllErrors()[0]);
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
