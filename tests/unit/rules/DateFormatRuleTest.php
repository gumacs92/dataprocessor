<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-29
 * Time: 02:07 AM
 */

namespace unit\rules;


use DateTime;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\DateFormatRule;

class DateFormatRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new DateFormatRule();
        $this->rule->setRuleName('dateFormat');

        $this->rule->checkArguments(['Y-m-d H:i:s']);
    }

    public function testDateFormatTrue()
    {
        $return = $this->rule->verify(new DateTime("11/28/2016 5:35 PM"));
        $data = $this->rule->getData();

        $this->assertEquals(true, $return);
        $this->assertEquals("2016-11-28 17:35:00", $data);
    }

    public function testDateFormatTrueWithError()
    {
        $return = $this->rule->verify(new DateTime("11/28/2016 5:35 PM"));
        $data = $this->rule->getData();

        $this->assertEquals(true, $return);
        $this->assertEquals("2016-11-28 17:35:00", $data);

    }
}
