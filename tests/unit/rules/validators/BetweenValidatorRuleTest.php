<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 09:17 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class BetweenRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testBetweenTypeError()
    {
        $this->expectException('\Processor\Exceptions\InvalidArgumentException');
        DataProcessor::init()->between(2, 'asd')->verify("asd", Errors::ALL);
    }

    public function testBetweenInRangeNotInclusive()
    {
        $return = DataProcessor::init()->between(2, 5, false)->verify(2);

        $this->assertEquals(false, $return);
    }

    public function testBetweenInRangeInclusive()
    {
        $return = DataProcessor::init()->between(2, 4)->verify(2);

        $this->assertEquals(true, $return);
    }

    public function testBetweenOutOfRange()
    {
        try {
            DataProcessor::init()->between(2, 4)->verify(5, Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;

            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('between'), ["min" => 2, "max" => 4]), $e->getErrors()['between']);
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
