<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-24
 * Time: 04:52 PM
 */

namespace Tests\Unit\Rules;


use Processor\Exceptions\RuleException;
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

    public function testPhoneTrueWithError()
    {
        $return = $this->rule->process("+36305997898", Errors::ALL);

        $this->assertEquals(true, $return);

    }

    public function testPhoneFalseWithError()
    {
        try {
            $return = $this->rule->process("123-aáéű webcam1", Errors::ALL);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("phone"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
