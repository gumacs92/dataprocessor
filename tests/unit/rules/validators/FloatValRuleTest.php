<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:22 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Exceptions\RuleException;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FloatValRule;

class FloatValRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var \Processor\Rules\AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new FloatValRule();
        $this->rule->setRuleName('floatVal');
    }

    public function testFloatValTrue()
    {
        $return = $this->rule->verify(10.1);

        $this->assertEquals(true, $return);
    }

    public function testFloatValTrueFromInt()
    {
        $return = $this->rule->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testFloatValTrueWithError()
    {
        $return = $this->rule->verify(10.1, true);

        $this->assertEquals(true, $return);
    }

    public function testFloatValFalseWithError()
    {
        try {
            $return = $this->rule->verify("asd", true);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("floatVal"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
