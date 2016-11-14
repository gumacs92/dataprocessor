<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-08
 * Time: 11:13 AM
 */

namespace Tests\Unit\Rules\Generic;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\RuleSettings;
use Tests\Helpers\Tools;

class OneOfRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testOneOfSuccessfulValidator(){
        $return = DataProcessor::init()->oneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->setTypeInt()->intType())->setNameForErrors('oneof')->verify("123", true);
        $value = DataProcessor::init()->getData();

        $this->assertEquals("123", $value);
    }

    public function testOneOfSuccessfulFilter(){
        $return = DataProcessor::init()->oneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->setTypeInt()->intType())->setNameForErrors('oneof')->verify("123asd", true);
        $value = DataProcessor::init()->getData();

        $this->assertEquals(123, $value);
    }

    public function testOneOfError(){
        try {
            DataProcessor::init()->oneOf(DataProcessor::init()->length(2, 4), DataProcessor::init()->floatType())->setNameForErrors('oneof')->verify("1", true);
        } catch (FailedProcessingException $e) {

            $errors = $e->getAllErrors();
            $this->assertEquals(3, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('oneOf'), $e->getAllErrors()[0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "oneof", "min" => 2, "max" => 4]), $e->getAllErrors()[1][0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "oneof"]), $e->getAllErrors()[2][0]);
        }
    }
}
