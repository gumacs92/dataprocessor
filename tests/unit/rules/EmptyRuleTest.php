<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-12-03
 * Time: 04:55 PM
 */

namespace Tests\Unit\Rules;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\EmptyRule;

class EmptyRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new EmptyRule();
    }

    public function testEmptyTrue()
    {
        $return = $this->rule->process(null);
        $return1 = $this->rule->process("");
        $return2 = $this->rule->process(0);
        $return3 = $this->rule->process([]);

        $this->assertEquals(true, $return);
        $this->assertEquals(true, $return1);
        $this->assertEquals(true, $return2);
        $this->assertEquals(true, $return3);
    }

    public function testEmptyFalse()
    {
        $return = $this->rule->process("asd");
        $return1 = $this->rule->process(1);
        $return2 = $this->rule->process([10]);

        $this->assertEquals(false, $return);
        $this->assertEquals(false, $return1);
        $this->assertEquals(false, $return2);
    }

    public function testEmptyFalseWithErrors()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $this->assertEquals(RuleSettings::getErrorSetting('empty'), $this->rule->getResultErrors()['empty']);
        $this->assertEquals(false, $return);
    }
}
