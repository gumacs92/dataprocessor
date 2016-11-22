<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 06:23 PM
 */

namespace Processor;



use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\RuleSettings;


class DataProcessorTest extends \PHPUnit_Framework_TestCase
{

    public function testComplexRule1(){
        $return = DataProcessor::init()->each(DataProcessor::init()->intType()->in([10, 11, 12]))->verify([10, 11, 10]);

        $this->assertEquals(true, $return);
    }

    public function testSetName()
    {
        try {
            $return = DataProcessor::init()->setTypeInt()->floatType()->setNameForErrors('alma')->verify("123asd", true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getOneError()));
            $search = "/({{)(name)(}})/";
            $replace = "alma";
            $msg = RuleSettings::getErrorSetting('floatType');
            $msg = preg_replace($search, $replace, $msg);
            $this->assertEquals($msg, $e->getOneError());

        }
    }

    public function testIntCastAndIntVal()
    {
        $this->expectException('\Processor\Exceptions\FailedProcessingException');
        $return = DataProcessor::init()->setTypeInt()->floatType()->verify("123asd", true);
    }

    public function testIntCast()
    {
        $return = DataProcessor::init()->setTypeInt()->verify("123asd", true);
        $value = DataProcessor::init()->getData();

        $this->assertEquals('integer', gettype($value));
        $this->assertEquals(123, $value);
    }

    public function testReturnNumericAndIntValError()
    {
        try {
            DataProcessor::init()->numeric()->intType()->verify("asd", true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('numeric'), $e->getAllErrors()[0]);
            $this->assertEquals(RuleSettings::getErrorSetting('intType'), $e->getAllErrors()[1]);

        }
    }

    public function testReturnNumericErrorOnly()
    {
        try {
            DataProcessor::init()->numeric()->intType()->verify("asd", true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getOneError()));
            $this->assertEquals(RuleSettings::getErrorSetting('numeric'), $e->getOneError());
        }
    }

    public function testInvalidArgumentExceptionTooManyArgument()
    {
        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
        $result = DataProcessor::init()->optional('asd', 10)->verify(10);
    }

    public function testInvalidArgumentExceptionNoArgumentExpected()
    {
        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
        $result = DataProcessor::init()->numeric('asd')->verify(10);
    }

    public function testInvalidArgumentExceptionArgumentTypeError()
    {
        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
        $result = DataProcessor::init()->optional("asd")->verify(10);
    }

    public function testInvalidRuleException()
    {
        $this->expectException('Processor\Exceptions\InvalidRuleException');
        $result = DataProcessor::init()->gasd(DataProcessor::init()->numeric())->verify(10);
    }


}
