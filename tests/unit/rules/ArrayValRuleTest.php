<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 04:43 PM
 */

namespace Tests\Unit\Rules;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\ArrayValRule;

class ArrayValRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new ArrayValRule();
    }

    public function testArrayValTrue()
    {
        $return = $this->rule->process([10]);

        $this->assertEquals(true, $return);
    }

    public function testArrayValFalse()
    {
        $return = $this->rule->process("asd");

        $this->assertEquals(false, $return);
    }

    public function testArrayValTrueWithError()
    {
        $return = $this->rule->process([1, 2, 3], Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testArrayValFalseWithError()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $this->assertEquals(RuleSettings::getErrorSetting("arrayVal"), $this->rule->getResultErrors()['arrayVal']);
        $this->assertEquals(false, $return);
    }
}
