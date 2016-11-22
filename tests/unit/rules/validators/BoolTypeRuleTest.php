<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:21 AM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Exceptions\RuleException;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\BoolValRule;

class BoolTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var \Processor\Rules\AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new BoolValRule();
        $this->rule->setRuleName('boolType');
    }

    public function testBoolTypeTrue()
    {
        $return = $this->rule->verify(true);

        $this->assertEquals(true, $return);
    }

    public function testBoolTypeFalse()
    {
        $return = $this->rule->verify("asd");

        $this->assertEquals(false, $return);
    }

    public function testBoolTypeTrueWithError()
    {
        $return = $this->rule->verify(false, false);

        $this->assertEquals(true, $return);
    }

    public function testBoolTypeFalseWithError()
    {
        try {
            $return = $this->rule->verify("asd", true);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("boolType"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
