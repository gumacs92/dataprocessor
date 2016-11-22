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
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class LengthRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testLengthTypeError()
    {
        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
        DataProcessor::init()->length(2, 4)->setNameForErrors('Length')->verify(1, true);
    }

    public function testLengthInRangeNotInclusive()
    {
        $return = DataProcessor::init()->length(2, 4, false)->setNameForErrors('Range')->verify("12");

        $this->assertEquals(false, $return);
    }

    public function testLengthInRangeInclusive()
    {
        $return = DataProcessor::init()->length(2, 4)->setNameForErrors('Range')->verify("12");

        $this->assertEquals(true, $return);
    }

    public function testLengthOutOfRange()
    {
        try {
            DataProcessor::init()->length(2, 4)->setNameForErrors('Range')->verify("1", true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getOneError()));
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["name" => "Range", "min" => 2, "max" => 4]), $e->getOneError());
        }
    }

    public function testLengthTypeFalse()
    {
        try {
            DataProcessor::init()->stringType()->length(0, 4)->setNameForErrors('Length')->verify(5, true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getOneError()));
            $this->assertEquals(2, sizeof($e->getAllErrors()));
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('stringType'), ["name" => "Length", "min" => 0, "max" => 4]), $e->getOneError());
        }
    }
}
