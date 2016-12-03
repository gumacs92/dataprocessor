<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-08
 * Time: 11:13 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\OneOfRule;
use Tests\Helpers\Tools;

class OneOfRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new OneOfRule(DataProcessor::init()->length(2, 4), DataProcessor::init()->floatType());
    }

    public function testOneOfTrueFirst()
    {
        $return = $this->rule->process("123", Errors::ALL);

        $this->assertEquals("123", $this->rule->getData());
    }

    public function testOneOfTrueSecond()
    {
        $return = $this->rule->process("1231.1", Errors::ALL);

        $this->assertEquals(1231.1, $this->rule->getData());
    }

    public function testOneOfAllError()
    {
        $return = $this->rule->process("adhfghfgf", Errors::ALL);

        $this->assertEquals(false, $return);
        $this->assertEquals(3, sizeof($this->rule->getResultErrors()['oneOf']));
        $this->assertEquals(RuleSettings::getErrorSetting('oneOf'), $this->rule->getResultErrors()['oneOf']['oneOf']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["min" => 2, "max" => 4]), $this->rule->getResultErrors()['oneOf']['oneOf0']['length']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), []), $this->rule->getResultErrors()['oneOf']['oneOf1']['floatType']);

    }
}
