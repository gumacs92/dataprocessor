<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 12:58 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class NoneOfRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testNoneOfTrue(){
        $return = DataProcessor::init()->noneOf(DataProcessor::init()->intType(), DataProcessor::init()->setTypeInt()->floatType())->setName('noneof')->verify(10.1);
        $value = DataProcessor::getReturnData();

        $this->assertEquals(true, $return);
        $this->assertEquals(10.1, $value);
    }

    public function testNoneOfFalse(){
        $return = DataProcessor::init()->noneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->floatType())->setName('noneof')->verify("1.1");

            $this->assertEquals(false, $return);
    }

    public function testNoneOfTrueWithErrors(){
        $return = DataProcessor::init()->noneOf(DataProcessor::init()->intType(), DataProcessor::init()->setTypeInt()->floatType())->setName('noneof')->verify(10.1, Errors::ALL);
        $value = DataProcessor::getReturnData();

        $this->assertEquals(true, $return);
        $this->assertEquals(10.1, $value);
    }

    public function testNoneOfFalseWithErrors(){
        try {
             DataProcessor::init()->noneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->setTypeInt()->intType())->setName('noneof')->verify("1.1", Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(3, sizeof($e->getErrors()['noneOf']));
            $this->assertEquals(RuleSettings::getErrorSetting('noneOf'), $e->getErrors()['noneOf']['noneOf']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "noneof", "min" => 2, "max" => 4]), $e->getErrors()['noneOf']['noneOf0']['length']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('setTypeInt'), ["name" => "noneof"]), $e->getErrors()['noneOf']['noneOf1']['setTypeInt']);
        }
    }
}
