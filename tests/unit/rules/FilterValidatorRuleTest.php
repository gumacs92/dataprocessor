<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-23
 * Time: 12:42 PM
 */

namespace Tests\Unit\Rules;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FilterValidatorRule;
use Tests\Helpers\Tools;

class FilterValidatorRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new FilterValidatorRule(FILTER_SANITIZE_EMAIL);
    }

    public function testFilterValidatorTrueFilter()
    {
        $return = $this->rule->process("asdf@gmailá.com");
        $data = $this->rule->getData();

        $this->assertEquals($data, 'asdf@gmail.com');
        $this->assertEquals(true, $return);
    }

    public function testFilterValidatorTrueValidator()
    {
        $this->rule = new FilterValidatorRule(FILTER_VALIDATE_EMAIL);
        $return = $this->rule->process("kissgeza@gmail.com");

        $this->assertEquals(true, $return);
    }

    public function testFilterValidatorFalseValidator()
    {
        $this->rule = new FilterValidatorRule(FILTER_VALIDATE_EMAIL);
        $return = $this->rule->process("12-aáéű@:.;,?!%");

        $this->assertEquals(false, $return);
    }

    public function testFilterValidatorTrueFilterWithErrors()
    {
        $return = $this->rule->process("áéű", Errors::ALL);
        $data = $this->rule->getData();

        $this->assertEquals($data, '');
        $this->assertEquals(true, $return);

    }

    public function testFilterValidatorFalseValidatorWithError()
    {
        $this->rule = new FilterValidatorRule(FILTER_VALIDATE_EMAIL);
        $return = $this->rule->process("asdf@gmail!.com", Errors::ALL);

        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('filterValidator'), ["filter" => FILTER_VALIDATE_EMAIL]), $this->rule->getResultErrors()['filterValidator']);
        $this->assertEquals(false, $return);
    }
}
