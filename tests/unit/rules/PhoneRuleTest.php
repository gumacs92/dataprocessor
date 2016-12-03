<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-24
 * Time: 04:52 PM
 */

namespace Tests\Unit\Rules;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\PhoneRule;

class PhoneRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new PhoneRule();
    }

    public function testPhoneTrue()
    {
        $return = $this->rule->process("+36-30-599-7898");

        $this->assertEquals(true, $return);
    }

    public function testPhoneFalse()
    {
        $return = $this->rule->process("12-aáéű@:.;,?!%");

        $this->assertEquals(false, $return);
    }

    public function testPhoneTrueWithAllError()
    {
        $return = $this->rule->process("+36305997898", Errors::ALL);

        $this->assertEquals(true, $return);

    }

    public function testPhoneFalseWithAllError()
    {
        $return = $this->rule->process("123-aáéű webcam1", Errors::ALL);

        $this->assertEquals(RuleSettings::getErrorSetting("phone"), $this->rule->getResultErrors()['phone']);

        $this->assertEquals(false, $return);
    }
}
