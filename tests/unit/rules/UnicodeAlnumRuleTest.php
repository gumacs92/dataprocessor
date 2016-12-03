<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:20 PM
 */

namespace Tests\Unit\Rules;


use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\UnicodeAlnumRule;

class UnicodeAlnumRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new UnicodeAlnumRule('-');
    }

    public function testUnicodeAlnumTrueExtraCharacters()
    {
        $return = $this->rule->process("éű");

        $this->assertEquals(true, $return);
    }

    public function testUnicodeAlnumTrue()
    {
        $return = $this->rule->process("áű1");

        $this->assertEquals(true, $return);
    }

    public function testUnicodeAlnumFalse()
    {
        $return = $this->rule->process("12-aáéű@:.;,?!%");

        $this->assertEquals(false, $return);
    }

    public function testUnicodeAlnumTrueWithAllError()
    {
        $return = $this->rule->process("áéű", Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testUnicodeAlnumFalseWithAllError()
    {
        $return = $this->rule->process("123-aáéű webcam1", Errors::ALL);

        $this->assertEquals(RuleSettings::getErrorSetting("unicodeAlnum"), $this->rule->getResultErrors()['unicodeAlnum']);

        $this->assertEquals(false, $return);
    }
}
