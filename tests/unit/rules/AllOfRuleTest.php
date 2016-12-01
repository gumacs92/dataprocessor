<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-08
 * Time: 11:17 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class AllOfRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testAllOfSuccessfulAll(){
        $return = DataProcessor::init()->allOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->setTypeInt()->intType())->setName('allof')->verify("123", Errors::ALL);
        $value = DataProcessor::getReturnData();

        $this->assertEquals(123, $value);
    }

    public function testAllOfOneError(){
        try {
            DataProcessor::init()->allOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->floatType())->setName('allof')->verify("111", Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getErrors()['allOf']));
            $this->assertEquals(RuleSettings::getErrorSetting('allOf'), $e->getErrors()['allOf']['allOf']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "allof"]), $e->getErrors()['allOf']['allOf0']['floatType']);
        }
    }

    public function testAllOfAllError(){
        try {
            DataProcessor::init()->allOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->floatType())->setName('allof')->verify("1", Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(3, sizeof($e->getErrors()['allOf']));
            $this->assertEquals(RuleSettings::getErrorSetting('allOf'), $e->getErrors()['allOf']['allOf']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "allof", "min" => 2, "max" => 4]), $e->getErrors()['allOf']['allOf0']['length']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "allof"]), $e->getErrors()['allOf']['allOf1']['floatType']);
        }
    }
}
