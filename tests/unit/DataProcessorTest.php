<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 06:23 PM
 */

namespace Processor;



use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
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
            $return = DataProcessor::init()->setTypeInt()->floatType()->setName('alma')->verify("123asd", Errors::ONE);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getErrors()));
            $search = "/({{)(name)(}})/";
            $replace = "alma";
            $msg = RuleSettings::getErrorSetting('floatType');
            $msg = preg_replace($search, $replace, $msg);
            $this->assertEquals($msg, $e->getErrors()['floatType']);

        }
    }

    public function testIntCastAndIntVal()
    {
        $this->expectException('\Processor\Exceptions\FailedProcessingException');
        $return = DataProcessor::init()->setTypeInt()->floatType()->verify("123asd", Errors::ALL);
    }

    public function testIntCast()
    {
        $return = DataProcessor::init()->setTypeInt()->verify("123asd", Errors::ALL);
        $value = DataProcessor::getReturnData();

        $this->assertEquals('integer', gettype($value));
        $this->assertEquals(123, $value);
    }

    public function testReturnNumericAndIntValError()
    {
        try {
            DataProcessor::init()->numeric()->intType()->verify("asd", Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('numeric'), $e->getErrors()['numeric']);
            $this->assertEquals(RuleSettings::getErrorSetting('intType'), $e->getErrors()['intType']);

        }
    }

    public function testReturnNumericErrorOnly()
    {
        try {
            DataProcessor::init()->numeric()->intType()->verify("asd", Errors::ONE);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('numeric'), $e->getErrors()['numeric']);
        }
    }

//    public function testInvalidArgumentExceptionTooManyArgument()
//    {
//        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
//        $result = DataProcessor::init()->optional('asd', 10)->verify(10);
//    }
//
//    public function testInvalidArgumentExceptionNoArgumentExpected()
//    {
//        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
//        $result = DataProcessor::init()->numeric('asd')->verify(10);
//    }
//
//    public function testInvalidArgumentExceptionArgumentTypeError()
//    {
//        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
//        $result = DataProcessor::init()->optional("asd")->verify(10);
//    }
//
//    public function testInvalidRuleException()
//    {
//        $this->expectException('Processor\Exceptions\InvalidRuleException');
//        $result = DataProcessor::init()->gasd(DataProcessor::init()->numeric())->verify(10);
//    }


}
