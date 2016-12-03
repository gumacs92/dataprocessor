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
use Processor\Rules\BoolTypeRule;

class BoolTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new BoolTypeRule();
    }

    public function testBoolTypeTrue()
    {
        $return = $this->rule->process(true);

        $this->assertEquals(true, $return);
    }

    public function testBoolTypeFalse()
    {
        $return = $this->rule->process("asd");

        $this->assertEquals(false, $return);
    }

    public function testBoolTypeTrueWithError()
    {
        $return = $this->rule->process(false, Errors::NONE);

        $this->assertEquals(true, $return);
    }

    public function testBoolTypeFalseWithError()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $return = false;
        $this->assertEquals(RuleSettings::getErrorSetting("boolType"), $this->rule->getMockedErrorMessage());

        $this->assertEquals(false, $return);
    }
}
