<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 03:28 AM
 */

namespace Tests\Unit\Rules\Generic;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\RuleSettings;
use Tests\Helpers\Tools;

class EachRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testEachSuccess()
    {
        $return = DataProcessor::init()->Each(DataProcessor::init()->FloatType())->setNameForErrors('each')->verify([10.1, 11.1, 12.1]);
        $this->assertEquals(true, $return);
    }

    public function testEachFalse()
    {
            $return = DataProcessor::init()->Each(DataProcessor::init()->FloatType())->setNameForErrors('each')->verify([10, 11, 12]);
            $this->assertEquals(false, $return);
    }

    public function testEachSuccessWithErrors()
    {
        $return = DataProcessor::init()->Each(DataProcessor::init()->FloatType())->setNameForErrors('each')->verify([10.1, 11.1, 12.1], true);
        $this->assertEquals(true, $return);

    }

    public function testEachFalseWithErrors()
    {
        try {
            DataProcessor::init()->Each(DataProcessor::init()->FloatType())->setNameForErrors('each')->verify([10.1, 11, 12], true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('each'), $e->getAllErrors()[0]);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "each"]), $e->getAllErrors()[1][0]);
        }
    }
}
