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
use Tests\Helpers\Tools;


class DataProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyStringWithoutOptional()
    {
        $return = DataProcessor::init()->numeric()->process("", Errors::ONE);

        $this->assertEquals(false, $return->isSuccess());
        $this->assertEquals(RuleSettings::getErrorSetting('empty'), $return->getErrors()['empty']);
    }

    public function testNullWithoutOptional()
    {
        $return = DataProcessor::init()->numeric()->process(null, Errors::ONE);

        $this->assertEquals(false, $return->isSuccess());
        $this->assertEquals(RuleSettings::getErrorSetting('empty'), $return->getErrors()['empty']);
    }

    public function testComplexRule1()
    {
        $return = DataProcessor::init()->each(DataProcessor::init()->intType()->in([10, 11, 12]))->process([10, 11, 10]);

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testSetNameEach()
    {
        $return = DataProcessor::init()->each(DataProcessor::init()->setTypeInt()->boolVal())->setName('alma')->process([0, 200], Errors::ONE);

        $this->assertEquals(1, sizeof($return->getErrors()));
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('boolVal'), [ 'name' => 'alma']), $return->getErrors()['each']['boolVal']);

    }

    public function testSetName()
    {
        $return = DataProcessor::init()->setTypeInt()->floatType()->setName('alma')->process("123asd", Errors::ONE);

        $this->assertEquals(1, sizeof($return->getErrors()));
        $search = "/({{)(name)(}})/";
        $replace = "alma";
        $msg = RuleSettings::getErrorSetting('floatType', RuleSettings::MODE_DEFAULT);
        $msg = preg_replace($search, $replace, $msg);
        $this->assertEquals($msg, $return->getErrors()['floatType']);

    }

    public function testIntCastAndIntVal()
    {
        $return = DataProcessor::init()->setTypeInt()->floatType()->process("123asd", Errors::ALL);

        $this->assertEquals(RuleSettings::getErrorSetting('floatType', RuleSettings::MODE_DEFAULT), $return->getErrors()['floatType']);
    }

    public function testIntCast()
    {
        $return = DataProcessor::init()->setTypeInt()->process("123asd", Errors::ALL);

        $this->assertEquals('integer', gettype($return->getData()));
        $this->assertEquals(123, $return->getData());
    }

    public function testReturnNumericAndIntValError()
    {
        try {
            DataProcessor::init()->numeric()->intType()->process("asd", Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('numeric', RuleSettings::MODE_DEFAULT), $e->getErrors()['numeric']);
            $this->assertEquals(RuleSettings::getErrorSetting('intType', RuleSettings::MODE_DEFAULT), $e->getErrors()['intType']);

        }
    }

    public function testReturnNumericErrorOnly()
    {
        try {
            DataProcessor::init()->numeric()->intType()->process("asd", Errors::ONE);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('numeric', RuleSettings::MODE_DEFAULT), $e->getErrors()['numeric']);
        }
    }

//    public function testInvalidArgumentExceptionTooManyArgument()
//    {
//        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
//        $result = DataProcessor::init()->optional('asd', 10)->process(10);
//    }
//
//    public function testInvalidArgumentExceptionNoArgumentExpected()
//    {
//        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
//        $result = DataProcessor::init()->numeric('asd')->process(10);
//    }
//
//    public function testInvalidArgumentExceptionArgumentTypeError()
//    {
//        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
//        $result = DataProcessor::init()->optional("asd")->process(10);
//    }
//
//    public function testInvalidRuleException()
//    {
//        $this->expectException('Processor\Exceptions\InvalidRuleException');
//        $result = DataProcessor::init()->gasd(DataProcessor::init()->numeric())->process(10);
//    }


}
