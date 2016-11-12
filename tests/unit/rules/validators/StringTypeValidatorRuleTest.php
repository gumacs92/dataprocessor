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

class StringTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testStringTypeTrue()
    {
        $return = DataProcessor::init()->StringType()->verify("123");

        $this->assertEquals(true, $return);
    }

    public function testStringTypeFalse()
    {
        $return = DataProcessor::init()->StringType()->verify(10);

        $this->assertEquals(false, $return);
    }

    public function testStringTypeTrueWithError()
    {
        $return = DataProcessor::init()->StringType()->verify("123", true);

        $this->assertEquals(true, $return);
    }

    public function testStringTypeFalseWithError()
    {
        try {
            DataProcessor::init()->StringType()->verify(10, true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("stringType"), $e->getAllErrors()[0]);
        } finally {
            $this->assertEquals(false, $return);
        }
    }


}
