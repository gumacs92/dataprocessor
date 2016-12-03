<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 06:12 PM
 */

namespace Tests\Unit\Rules;

use Processor\DataProcessor;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\OptionalRule;

class OptionalRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new OptionalRule(DataProcessor::init()->numeric());
    }

    public function testOptionalTrueNullValue()
    {
        $return = $this->rule->process(null);

        $this->assertEquals(true, $return);
    }

    public function testOptionalTrueEmptyStringValue()
    {
        $return = $this->rule->process("");

        $this->assertEquals(true, $return);
    }

    public function testOptionalTrueEmptyIntegerValue()
    {
        $return = $this->rule->process(0);

        $this->assertEquals(true, $return);
    }

    public function testOptionalTrueNotNullinteger()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(true, $return);
    }


    public function testOptionalFalseNotNullString()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $this->assertEquals(2, sizeof($this->rule->getResultErrors()['optional']));
        $this->assertEquals(RuleSettings::getErrorSetting("numeric"), $this->rule->getResultErrors()['optional']['optional0']['numeric']);

        $this->assertEquals(false, $return);
    }
}
