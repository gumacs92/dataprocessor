<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 12:58 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\NoneOfRule;
use Tests\Helpers\Tools;

class NoneOfRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new NoneOfRule(DataProcessor::init()->numeric(), DataProcessor::init()->floatVal()->setTypeFloat()->floatType());
    }

    public function testNoneOfTrue()
    {
        $return = $this->rule->process("asd");

        $this->assertEquals(true, $return);
        $this->assertEquals("asd", $this->rule->getData());
    }

    public function testNoneOfFalse()
    {
        $return = $this->rule->process(1);

        $this->assertEquals(false, $return);
    }

    public function testNoneOfTrueWithErrors()
    {
        $return = $this->rule->process("asd", Errors::ALL);

        $this->assertEquals(true, $return);
        $this->assertEquals("asd", $this->rule->getData());
    }

    public function testNoneOfFalseWithErrors()
    {
        $this->rule->process("1.1", Errors::ALL);

        $this->assertEquals(3, sizeof($this->rule->getResultErrors()['noneOf']));
        $this->assertEquals(RuleSettings::getErrorSetting('noneOf'), $this->rule->getResultErrors()['noneOf']['noneOf']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('numeric', RuleSettings::MODE_NEGATED), ["min" => 2, "max" => 4]), $this->rule->getResultErrors()['noneOf']['noneOf0']['numeric']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatVal', RuleSettings::MODE_NEGATED), []), $this->rule->getResultErrors()['noneOf']['noneOf1']['floatVal']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('setTypeFloat', RuleSettings::MODE_NEGATED), []), $this->rule->getResultErrors()['noneOf']['noneOf1']['setTypeFloat']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType', RuleSettings::MODE_NEGATED), []), $this->rule->getResultErrors()['noneOf']['noneOf1']['floatType']);
    }
}
