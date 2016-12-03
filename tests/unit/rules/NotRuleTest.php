<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-12-03
 * Time: 05:41 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\NotRule;

class NotRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new NotRule(DataProcessor::init()->numeric()->intVal());
    }

    public function testNotTrue()
    {
        $return = $this->rule->process("asd");

        $this->assertEquals(true, $return);
    }

    public function testNotFalseWithOneErrors()
    {
        $return = $this->rule->process(10, Errors::ONE);

        $this->assertEquals(false, $return);
        $this->assertEquals(2, sizeof($this->rule->getResultErrors()['not']));
        $this->assertEquals(RuleSettings::getErrorSetting("numeric", RuleSettings::MODE_NEGATED), $this->rule->getResultErrors()['not']['numeric']);
    }

    public function testNotFalseWithAllErrors()
    {
        $return = $this->rule->process(10, Errors::ALL);

        $this->assertEquals(false, $return);
        $this->assertEquals(2, sizeof($this->rule->getResultErrors()['not']));
        $this->assertEquals(RuleSettings::getErrorSetting("numeric", RuleSettings::MODE_NEGATED), $this->rule->getResultErrors()['not']['not0']['numeric']);
        $this->assertEquals(RuleSettings::getErrorSetting("intVal", RuleSettings::MODE_NEGATED), $this->rule->getResultErrors()['not']['not0']['intVal']);
    }

}
