<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 02:15 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\LengthRule;
use Tests\Helpers\Tools;

class LengthRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new LengthRule(2, 4, false);
    }

    public function testLengthTrueNotInclusive()
    {
        $this->rule = new LengthRule(2, 4, false);
        $return = $this->rule->process([1, 2]);

        $this->assertEquals(false, $return);
    }

    public function testLengthTrueInclusive()
    {
        $return = $this->rule->process([1, 2, 3]);

        $this->assertEquals(true, $return);
    }

    public function testLengthFalseOutOfRangeArray()
    {
        $this->rule->process([10], Errors::ALL);

        $this->assertEquals(1, sizeof($this->rule->getResultErrors()));
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["min" => 2, "max" => 4]), $this->rule->getResultErrors()['length']);
    }

    public function testLengthFalseOutOfRangeString()
    {
        $this->rule->process("5", Errors::ALL);

        $this->assertEquals(1, sizeof($this->rule->getResultErrors()));
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('length'), ["min" => 2, "max" => 4]), $this->rule->getResultErrors()['length']);
    }
}
