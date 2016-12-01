<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 06:12 PM
 */

namespace Tests\Unit\Rules;

use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\RuleSettings;

class OptionalRuleTest extends \PHPUnit_Framework_TestCase
{

    public function testNullValue()
    {
        $return = DataProcessor::init()->optional(DataProcessor::init()->numeric())->verify(null);

        $this->assertEquals(true, $return);
    }

    public function testEmptyStringValue()
    {
        $return = DataProcessor::init()->optional(DataProcessor::init()->numeric())->verify("");

        $this->assertEquals(true, $return);
    }

    public function testEmptyIntegerValue()
    {
        $return = DataProcessor::init()->optional(DataProcessor::init()->numeric())->verify(0);

        $this->assertEquals(true, $return);
    }

    public function testOptionalNotNullTrue()
    {
        $result = DataProcessor::init()->optional(DataProcessor::init()->numeric())->verify(10);

        $this->assertEquals(true, $result);
    }


    public function testOptionalNotNullFalse()
    {
        try {
            $result = DataProcessor::init()->optional(DataProcessor::init()->numeric())->verify("asd");
        } catch (FailedProcessingException $e) {

            $return = false;
            $this->assertEquals(1, sizeof($e->getErrors()['optional']));
            $this->assertEquals(RuleSettings::getErrorSetting("numeric"), $e->getAllErrors()['optional'][['optional0']['nuemric']]);
        } finally {
            $this->assertEquals(false, $result);
        }
    }
}
