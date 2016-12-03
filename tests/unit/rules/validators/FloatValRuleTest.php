<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:22 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FloatValRule;

class FloatValRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new FloatValRule();
    }

    public function testFloatValTrue()
    {
        $return = $this->rule->process(10.1);

        $this->assertEquals(true, $return);
    }

    public function testFloatValTrueFromInt()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(true, $return);
    }

    public function testFloatValTrueWithError()
    {
        $return = $this->rule->process(10.1, Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testFloatValFalseWithError()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $return = false;
        $this->assertEquals(RuleSettings::getErrorSetting("floatVal"), $this->rule->getMockedErrorMessage());

        $this->assertEquals(false, $return);
    }
}
