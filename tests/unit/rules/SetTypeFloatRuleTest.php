<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:23 AM
 */

namespace Tests\Unit\Rules;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\SetTypeFloatRule;

class SetTypeFloatRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new SetTypeFloatRule();
    }

    public function testSetTypeFloatTrue()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeFloatTrueFromInt()
    {
        $return = $this->rule->process(10);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeFloatTrueWithError()
    {
        $return = $this->rule->process(10, Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testSetTypeFloatTrueFromStringsWithError()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $this->assertEquals(true, $return);
    }
}
