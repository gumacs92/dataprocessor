<?php
use Processor\Rules\Abstraction\AbstractRule;

/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 04:05 PM
 */

namespace Tests\Unit\Rules;

use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\NumericRule;

class NumericRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new NumericRule();
    }

    public function testNumericTrueWithErrors()
    {
        $return = $this->rule->process(10, Errors::ALL);
        $return1 = $this->rule->process("10", Errors::ALL);

        $this->assertEquals(true, $return);
        $this->assertEquals(true, $return1);
    }

    public function testNumericTrueFalseWithErrors()
    {
        $return = $this->rule->process("s", Errors::ALL);

        $this->assertEquals(RuleSettings::getErrorSetting("numeric"), $this->rule->getResultErrors()["numeric"]);
        $this->assertEquals(false, $return);
    }

    public function testNumericTrue()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(true, $return);
    }

    public function testNumericFalse()
    {
        $return = $this->rule->process("s");

        $this->assertEquals(false, $return);
    }

    public function testNumericTrueString()
    {
        $return = $this->rule->process("10");

        $this->assertEquals(true, $return);
    }
}
