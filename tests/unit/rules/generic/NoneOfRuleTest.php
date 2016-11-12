<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 12:58 AM
 */

namespace Tests\Unit\Rules\Generic;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\RuleSettings;
use Tests\Helpers\Tools;

class NoneOfRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testNoneOfTrue(){
        $return = DataProcessor::init()->NoneOf(DataProcessor::init()->IntType(), DataProcessor::init()->IntCast()->FloatType())->setNameForErrors('noneof')->verify(10.1);
        $value = DataProcessor::init()->getData();

        $this->assertEquals(true, $return);
        $this->assertEquals(10.1, $value);
    }

    public function testNoneOfFalse(){
            $return = DataProcessor::init()->NoneOf(DataProcessor::init()->Length(2, 4), DataProcessor::init()->FloatType())->setNameForErrors('noneof')->verify("1.1");

            $this->assertEquals(false, $return);
    }

    public function testNoneOfTrueWithErrors(){
        $return = DataProcessor::init()->NoneOf(DataProcessor::init()->IntType(), DataProcessor::init()->IntCast()->FloatType())->setNameForErrors('noneof')->verify(10.1, true);
        $value = DataProcessor::init()->getData();

        $this->assertEquals(true, $return);
        $this->assertEquals(10.1, $value);
    }

    public function testNoneOfFalseWithErrors(){
        try {
            DataProcessor::init()->NoneOf(DataProcessor::init()->Length(2, 4), DataProcessor::init()->IntCast()->IntType())->setNameForErrors('noneof')->verify("1.1", true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(3, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('noneOf'), $e->getAllErrors()[0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "noneof", "min" => 2, "max" => 4]), $e->getAllErrors()[1][0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('intCast'), ["name" => "noneof"]), $e->getAllErrors()[2][0]);
        }
    }
}
