<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-08
 * Time: 11:13 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class OneOfRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testOneOfSuccessfulValidator(){
        $return = DataProcessor::init()->oneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->setTypeInt()->intType())->setName('oneof')->verify("123", Errors::ALL);
        $value = DataProcessor::getReturnData();

        $this->assertEquals("123", $value);
    }

    public function testOneOfSuccessfulFilter(){
        $return = DataProcessor::init()->oneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->setTypeInt()->intType())->setName('oneof')->verify("123asd", Errors::ALL);
        $value = DataProcessor::getReturnData();

        $this->assertEquals(123, $value);
    }

    public function testOneOfError(){
        try {
            DataProcessor::init()->oneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->floatType())->setName('oneof')->verify("1", Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(3, sizeof($e->getErrors()['oneOf']));
            $this->assertEquals(RuleSettings::getErrorSetting('oneOf'), $e->getErrors()['oneOf']['oneOf']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "oneof", "min" => 2, "max" => 4]), $e->getErrors()['oneOf']['oneOf0']['length']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "oneof"]), $e->getErrors()['oneOf']['oneOf1']['floatType']);
        }
    }
}
