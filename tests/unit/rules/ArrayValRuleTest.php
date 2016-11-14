<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 04:43 PM
 */

namespace Tests\Unit\Rules;


use Processor\Exceptions\RuleException;
use Processor\Rules\ArrayValRule;
use Processor\Rules\RuleSettings;

class ArrayValRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var \Processor\Rules\AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new ArrayValRule();
        $this->rule->setRuleName('arrayVal');
    }

    public function testArrayValTrue()
    {
        $return = $this->rule->verify([]);

        $this->assertEquals(true, $return);
    }

    public function testArrayValFalse()
    {
        $return = $this->rule->verify("asd");

        $this->assertEquals(false, $return);
    }

    public function testArrayValTrueWithError()
    {
        $return = $this->rule->verify([1, 2, 3], true);

        $this->assertEquals(true, $return);
    }

    public function testArrayValFalseWithError()
    {
        try {
            $return = $this->rule->verify("asd", true);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("arrayVal"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
