<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:23 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\SetTypeBoolRule;

class SetTypeBoolRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var \Processor\Rules\AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new SetTypeBoolRule();
        $this->rule->setRuleName('setTypeBool');
    }

    public function testSetTypeBoolTrue()
    {
        $return = $this->rule->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeBoolTrueFromFloat()
    {
        $return = $this->rule->verify(10.1);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeBoolTrueWithError()
    {
        $return = $this->rule->verify(10, true);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeBoolTrueFromStringsWithError()
    {
        try {
            $return = $this->rule->verify("asd", true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("setTypeBool"), $e->getAllErrors()[0]);
        } finally {
            $this->assertEquals(true, $return);
        }
    }
}
