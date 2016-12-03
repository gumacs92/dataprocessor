<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-08
 * Time: 11:17 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\AllOfRule;
use Tests\Helpers\Tools;

class AllOfRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new AllOfRule(DataProcessor::init()->length(2, 4), DataProcessor::init()->intVal()->intType());
    }

    public function testAllOfSuccessfulAll()
    {
        $return = $this->rule->process("123", Errors::ALL);

        $this->assertEquals(123, $this->rule->getData());
    }

    public function testAllOfOneError()
    {
        $this->rule->process("111.111", Errors::ONE);

        $this->assertEquals(3, sizeof($this->rule->getResultErrors()['allOf']));
        $this->assertEquals(RuleSettings::getErrorSetting('allOf'), $this->rule->getResultErrors()['allOf']['allOf']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["min" => 2, "max" => 4]), $this->rule->getResultErrors()['allOf']['length']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('intVal'), []), $this->rule->getResultErrors()['allOf']['intVal']);

    }

    public function testAllOfAllError()
    {
        $this->rule->process("111.111", Errors::ALL);

        $this->assertEquals(3, sizeof($this->rule->getResultErrors()['allOf']));
        $this->assertEquals(RuleSettings::getErrorSetting('allOf', RuleSettings::MODE_DEFAULT), $this->rule->getResultErrors()['allOf']['allOf']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["min" => 2, "max" => 4]), $this->rule->getResultErrors()['allOf']['allOf0']['length']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('intVal'), []), $this->rule->getResultErrors()['allOf']['allOf1']['intVal']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('intType'), []), $this->rule->getResultErrors()['allOf']['allOf1']['intType']);
    }
}
