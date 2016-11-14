<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:23 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\RuleSettings;
use Processor\Rules\SetTypeStringRule;

class SetTypeStringRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var \Processor\Rules\AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new SetTypeStringRule();
        $this->rule->setRuleName('setTypeString');
    }

    public function testSetTypeStringTrueFromInt()
    {
        $return = $this->rule->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeStringTrueFromFloat()
    {
        $return = $this->rule->verify(10.1);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeStringTrueFromIntWithError()
    {
        $return = $this->rule->verify(10, true);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeStringTrueFromBoolWithError()
    {
        try {
            $return = $this->rule->verify(false, true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("setTypeString"), $e->getAllErrors()[0]);
        } finally {
            $this->assertEquals(true, $return);
        }
    }
}
