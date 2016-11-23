<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-23
 * Time: 12:42 PM
 */

namespace Tests\Unit\Rules;


use Processor\Exceptions\RuleException;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FilterValidatorRule;
use Tests\Helpers\Tools;

class FilterValidatorRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new FilterValidatorRule();
        $this->rule->setRuleName('filterValidator');
        $this->rule->checkArguments([FILTER_SANITIZE_EMAIL]);
    }

    public function testFilterValidatorTrueFilter()
    {
        $return = $this->rule->verify("asdf@gmailá.com");
        $data = FilterValidatorRule::getData();

        $this->assertEquals($data, 'asdf@gmail.com');
        $this->assertEquals(true, $return);
    }

    public function testFilterValidatorTrueValidator()
    {
        $this->rule->checkArguments([FILTER_VALIDATE_EMAIL]);
        $return = $this->rule->verify("kissgeza@gmail.com");

        $this->assertEquals(true, $return);
    }

    public function testFilterValidatorFalseValidator()
    {
        $this->rule->checkArguments([FILTER_VALIDATE_EMAIL]);
        $return = $this->rule->verify("12-aáéű@:.;,?!%");

        $this->assertEquals(false, $return);
    }

    public function testFilterValidatorTrueFilterWithErrors()
    {
        $return = $this->rule->verify("áéű", true);
        $data = FilterValidatorRule::getData();

        $this->assertEquals($data, '');
        $this->assertEquals(true, $return);

    }

    public function testFilterValidatorFalseValidatorWithError()
    {
        $this->rule->checkArguments([FILTER_VALIDATE_EMAIL]);
        try {
            $this->rule->verify("asdf@gmail!.com", true);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('filterValidator'), ["filter" => FILTER_VALIDATE_EMAIL]), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
