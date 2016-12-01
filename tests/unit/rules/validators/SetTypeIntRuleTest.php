<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:23 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\SetTypeIntRule;

class SetTypeIntRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var \Processor\Rules\AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new SetTypeIntRule();
    }

    public function testSetTypeIntTrue()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeIntTrueFromFloat()
    {
        $return = $this->rule->process(10.1);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeIntTrueWithError()
    {
        $return = $this->rule->process(10, Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeIntTrueFromStringsWithError()
    {
        try {
            $return = $this->rule->process("asd", Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("setTypeInt"), $e->getAllErrors()[0]);
        } finally {
            $this->assertEquals(true, $return);
        }
    }
}
