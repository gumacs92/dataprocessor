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
use Processor\Rules\IntValRule;

class IntValRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new IntValRule();
    }

    public function testIntValTrue()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(true, $return);
    }

    public function testIntValFalse()
    {
        $return = $this->rule->process(10.1);

        $this->assertEquals(false, $return);
    }

    public function testIntValTrueWithError()
    {
        $return = $this->rule->process(10, Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testIntValFalseWithError()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $this->rule->getMockedErrorMessage();
        $this->assertEquals(RuleSettings::getErrorSetting("intVal"), $this->rule->getMockedErrorMessage());

        $this->assertEquals(false, $return);
    }
}
