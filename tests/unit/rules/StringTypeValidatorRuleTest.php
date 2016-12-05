<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 08:41 PM
 */

namespace Tests\Unit\Rules;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\StringTypeRule;

class StringTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new StringTypeRule();
    }

    public function testStringTypeTrue()
    {
        $return = $this->rule->process("123");

        $this->assertEquals(true, $return);
    }

    public function testStringTypeFalse()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(false, $return);
    }

    public function testStringTypeTrueWithError()
    {
        $return = $this->rule->process("123", Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testStringTypeFalseWithError()
    {
        $return = $this->rule->process(10, Errors::ALL);

        $this->assertEquals(1, sizeof($this->rule->getResultErrors()['stringType']));
        $this->assertEquals(RuleSettings::getErrorSetting("stringType"), $this->rule->getResultErrors()['stringType']);

        $this->assertEquals(false, $return);

    }
}
