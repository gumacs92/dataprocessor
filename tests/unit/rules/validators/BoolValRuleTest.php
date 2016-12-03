<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:21 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\BoolValRule;

class BoolValRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new BoolValRule();
    }

    public function testBoolValTrue()
    {
        $return = $this->rule->process("true");

        $this->assertEquals(true, $return);
    }

    public function testBoolValFalse()
    {
        $return = $this->rule->process("asd");

        $this->assertEquals(false, $return);
    }

    public function testBoolValTrueWithError()
    {
        $return = $this->rule->process("no", false);

        $this->assertEquals(true, $return);
    }

    public function testBoolValFalseWithError()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $return = false;
        $this->assertEquals(RuleSettings::getErrorSetting("boolVal"), $this->rule->getMockedErrorMessage());

        $this->assertEquals(false, $return);
    }
}
