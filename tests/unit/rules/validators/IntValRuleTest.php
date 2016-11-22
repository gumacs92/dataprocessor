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
use Processor\Rules\IntValRule;

class IntValRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var \Processor\Rules\AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new IntValRule();
        $this->rule->setRuleName('intVal');
    }

    public function testIntValTrue()
    {
        $return = $this->rule->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testIntValFalse()
    {
        $return = $this->rule->verify(10.1);

        $this->assertEquals(false, $return);
    }

    public function testIntValTrueWithError()
    {
        $return = $this->rule->verify(10, true);

        $this->assertEquals(true, $return);
    }

    public function testIntValFalseWithError()
    {
        try {
            $return = $this->rule->verify("asd", true);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("intVal"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
