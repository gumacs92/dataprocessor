<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 02:15 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class LengthRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testLengthTypeError()
    {
        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
        DataProcessor::init()->length(2, 'asd')->setName('Length')->verify(1, Errors::ALL);
    }

    public function testLengthInRangeNotInclusive()
    {
        $return = DataProcessor::init()->length(2, 4, false)->setName('Range')->verify([1,2]);

        $this->assertEquals(false, $return);
    }

    public function testLengthInRangeInclusive()
    {
        $return = DataProcessor::init()->length(2, 4)->setName('Range')->verify([1,2,3]);

        $this->assertEquals(true, $return);
    }

    public function testLengthOutOfRange()
    {
        try {
            DataProcessor::init()->length(2, 4)->setName('Range')->verify([], Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "Range", "min" => 2, "max" => 4]), $e->getErrors()['length']);
        }
    }

    public function testLengthTypeFalse()
    {
        try {
            DataProcessor::init()->stringType()->length(0, 4)->setName('Length')->verify(5, Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(2, sizeof($e->getErrors()));
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('stringType'), ["name" => "Length", "min" => 0, "max" => 4]), $e->getErrors()['stringType']);
        }
    }
}
