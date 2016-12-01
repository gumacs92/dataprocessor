<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 04:43 PM
 */

namespace Tests\Unit\Rules;


use Processor\Exceptions\RuleException;
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
        $return = $this->rule->process([]);

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
        try {
            $return = $this->rule->process("asd", Errors::ALL);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("arrayVal"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
