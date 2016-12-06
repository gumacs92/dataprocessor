<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-29
 * Time: 02:07 AM
 */

namespace Tests\Unit\Rules;


use DateTime;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\DateTimeFormatRule;

class DateTimeFormatRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var AbstractRule $rule */
    private $rule;

    public function setUp()
    {
        $this->rule = new DateTimeFormatRule('Y-m-d H:i:s');
    }

    public function testDateTimeFormatTrue()
    {
        $return = $this->rule->process(new DateTime("11/28/2016 5:35 PM"));
        $data = $this->rule->getData();

        $this->assertEquals(true, $return);
        $this->assertEquals("2016-11-28 17:35:00", $data);
    }

    public function testDateTimeFormatTrueWithError()
    {
        $return = $this->rule->process(new DateTime("11/28/2016 5:35 PM"));
        $data = $this->rule->getData();

        $this->assertEquals(true, $return);
        $this->assertEquals("2016-11-28 17:35:00", $data);

    }

    public function testDateTimeFormatTrueLocale()
    {
        $this->rule = new DateTimeFormatRule('%Y %B %d', 'hu');
        $return = $this->rule->process(new DateTime("03/28/2016 5:35 PM"));
        $data = $this->rule->getData();

        $this->assertEquals(true, $return);
        $this->assertEquals("2016 március 28", $data);
    }
}
