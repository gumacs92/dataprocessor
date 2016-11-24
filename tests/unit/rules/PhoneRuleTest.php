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
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\PhoneRule;

class PhoneRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new PhoneRule();
        $this->rule->setRuleName('phone');
    }

    public function testPhoneTrue()
    {
        $return = $this->rule->verify("+36-30-599-7898");

        $this->assertEquals(true, $return);
    }

    public function testPhoneFalse()
    {
        $return = $this->rule->verify("12-aáéű@:.;,?!%");

        $this->assertEquals(false, $return);
    }

    public function testPhoneTrueWithError()
    {
        $return = $this->rule->verify("+36305997898", true);

        $this->assertEquals(true, $return);

    }

    public function testPhoneFalseWithError()
    {
        try {
            $this->rule->verify("123-aáéű webcam1", true);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("phone"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
