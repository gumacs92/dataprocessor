<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 01:20 PM
 */

namespace Tests\Unit\Rules;


use Processor\Exceptions\RuleException;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\UnicodeAlnumRule;

class UnicodeAlnumRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new UnicodeAlnumRule();
        $this->rule->setRuleName('unicodeAlnum');
        $this->rule->checkArguments(['-']);
    }

    public function testUnicodeAlnumTrueExtraCharacters()
    {
        $return = $this->rule->verify("éű");

        $this->assertEquals(true, $return);
    }

    public function testUnicodeAlnumTrue()
    {
        $return = $this->rule->verify("áű1");

        $this->assertEquals(true, $return);
    }

    public function testUnicodeAlnumFalse()
    {
        $return = $this->rule->verify("12-aáéű@:.;,?!%");

        $this->assertEquals(false, $return);
    }

    public function testUnicodeAlnumTrueWithError()
    {
        $return = $this->rule->verify("áéű", true);

        $this->assertEquals(true, $return);

    }

    public function testUnicodeAlnumFalseWithError()
    {
        try {
            $this->rule->verify("123-aáéű webcam1", true);
        } catch (RuleException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrorMessage()));
            $this->assertEquals(RuleSettings::getErrorSetting("unicodeAlnum"), $e->getErrorMessage());
        } finally {
            $this->assertEquals(false, $return);
        }
    }
}
