<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 03:28 AM
 */

namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\EachRule;
use Tests\Helpers\Tools;

class EachRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new EachRule(DataProcessor::init()->floatType());
    }

    public function testEachTrue()
    {
        $return = $this->rule->process([10.1, 11.1, 12.1]);
        $this->assertEquals(true, $return);
    }

    public function testEachFalse()
    {
        $return = $this->rule->process([10, 11, 12]);
        $this->assertEquals(false, $return);
    }

    public function testEachTrueWithErrors()
    {
        $return = $this->rule->process([10.1, 11.1, 12.1], Errors::ALL);
        $this->assertEquals(true, $return);

    }

    public function testEachFalseWithErrors()
    {
        $this->rule->process([10.1, 11, 12], Errors::ALL);

        $this->assertEquals(2, sizeof($this->rule->getResultErrors()['each']));
        $this->assertEquals(RuleSettings::getErrorSetting('each'), $this->rule->getResultErrors()['each']['each']);
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('floatType'), []), $this->rule->getResultErrors()['each']['each0']['floatType']);

    }
}
