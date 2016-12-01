<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 03:28 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class EachRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testEachSuccess()
    {
        $return = DataProcessor::init()->each(DataProcessor::init()->floatType())->setName('each')->verify([10.1, 11.1, 12.1]);
        $this->assertEquals(true, $return);
    }

    public function testEachFalse()
    {
        $return = DataProcessor::init()->each(DataProcessor::init()->floatType())->setName('each')->verify([10, 11, 12]);
            $this->assertEquals(false, $return);
    }

    public function testEachSuccessWithErrors()
    {
        $return = DataProcessor::init()->each(DataProcessor::init()->floatType())->setName('each')->verify([10.1, 11.1, 12.1], Errors::ALL);
        $this->assertEquals(true, $return);

    }

    public function testEachFalseWithErrors()
    {
        try {
            DataProcessor::init()->each(DataProcessor::init()->floatType())->setName('each')->verify([10.1, 11, 12], Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getErrors()['each']));
            $this->assertEquals(RuleSettings::getErrorSetting('each'), $e->getErrors()['each']['each']);
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), ["name" => "each"]), $e->getErrors()['each']['each0']['floatType']);
        }
    }
}
