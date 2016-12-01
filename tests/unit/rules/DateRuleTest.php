<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-29
 * Time: 01:13 AM
 */

namespace Tests\Unit\Rules;


use Processor\Exceptions\RuleException;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\DateRule;
use Tests\Helpers\Tools;

class DateRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new DateRule("m/d/Y h:i a", false);
    }

    public function testDateTrue()
    {
        $return = $this->rule->process(new \DateTime());
        $return1 = $this->rule->process("11/28/2016 5:35 PM");

        $this->assertEquals(true, $return);
        $this->assertEquals(true, $return1);
    }

    public function testDateFalse()
    {
        $return = $this->rule->process("12-aáéű@:.;,?!%");

        $this->assertEquals(false, $return);
    }

    public function testDateTrueWithError()
    {
        $this->rule = new DateRule();
        $return = $this->rule->process("now", Errors::ALL);

        $this->assertEquals(true, $return);

    }

    public function testDateFalseWithError()
    {
        try {
            $return = $this->rule->process("123-aáéű webcam1", Errors::ALL);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting("date"), ["format" => "m/d/Y h:i a"]), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
