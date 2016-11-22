<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 04:05 PM
 */

namespace Tests\Unit\Rules\Validators;

use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\NumericRule;

class NumericRuleTest extends \PHPUnit_Framework_TestCase
{
    private static $rule;

    public static function setUpBeforeClass()
    {
        self::$rule = new NumericRule();
        self::$rule->setRuleName('numeric');
    }

    public function testValueIsNumberWithErrors()
    {
        $return = DataProcessor::init()->numeric()->verify(10, true);
        $return1 = DataProcessor::init()->numeric()->verify("10", true);

        $this->assertEquals(true, $return);
        $this->assertEquals(true, $return1);
    }

    public function testValueIsNotNumberWithErrors()
    {
        try{
            $return = DataProcessor::init()->numeric()->verify("s", true);
        }catch(FailedProcessingException $e){
            $this->assertEquals(RuleSettings::getErrorSetting("numeric"), $e->getOneError());
        }
    }

    public function testValueIsNumber()
    {
        $return = DataProcessor::init()->numeric()->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testValueIsNotNumber()
    {
        $return = DataProcessor::init()->numeric()->verify("s");

        $this->assertEquals(false, $return);
    }

    public function testValueIsStringNumber()
    {
        $return = DataProcessor::init()->numeric()->verify("10");

        $this->assertEquals(true, $return);
    }
}
