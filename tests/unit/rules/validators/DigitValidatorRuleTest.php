<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 08:11 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;

class DigitRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testDigitTrueExtraCharacters(){
        $return = DataProcessor::init()->digit("asd")->verify("123asd");

        $this->assertEquals(true, $return);
    }

    public function testDigitTrue(){
        $return = DataProcessor::init()->digit()->verify("123");

        $this->assertEquals(true, $return);
    }

    public function testDigitFalse(){
        $return = DataProcessor::init()->digit()->verify("12a");

        $this->assertEquals(false, $return);
    }

    public function testDigitTrueWithError(){
        $return = DataProcessor::init()->digit()->verify("123", Errors::ALL);

        $this->assertEquals(true, $return);

    }

    public function testDigitFalseWithError(){
        try{
            DataProcessor::init()->digit()->verify("12a", Errors::ALL);
        } catch(FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("digit"), $e->getErrors()["digit"]);
        }finally{
            $this->assertEquals(false, $return);
        }
    }
}
