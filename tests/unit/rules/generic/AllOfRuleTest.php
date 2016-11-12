<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-08
 * Time: 11:17 AM
 */

namespace Tests\Unit\Rules\Generic;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\RuleSettings;
use Tests\Helpers\Tools;

class AllOfRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testAllOfSuccessfulAll(){
        $return = DataProcessor::init()->AllOf(DataProcessor::init()->Length(2, 4), DataProcessor::init()->IntCast()->IntType())->setNameForErrors('allof')->verify("123", true);
        $value = DataProcessor::init()->getData();

        $this->assertEquals(123, $value);
    }

    public function testAllOfOneError(){
        try {
            DataProcessor::init()->AllOf(DataProcessor::init()->Length(2, 4), DataProcessor::init()->FloatType())->setNameForErrors('allof')->verify("111", true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('allOf'), $e->getAllErrors()[0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "allof"]), $e->getAllErrors()[1][0]);
        }
    }

    public function testAllOfAllError(){
        try {
            DataProcessor::init()->AllOf(DataProcessor::init()->Length(2, 4), DataProcessor::init()->FloatType())->setNameForErrors('allof')->verify("1", true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(3, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('allOf'), $e->getAllErrors()[0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "allof", "min" => 2, "max" => 4]), $e->getAllErrors()[1][0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "allof"]), $e->getAllErrors()[2][0]);
        }
    }
}
