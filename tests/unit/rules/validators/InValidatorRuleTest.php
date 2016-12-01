<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 06:07 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;

class InRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testInTrue(){
        $return = DataProcessor::init()->in([10, 11, 12])->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testInFalse(){
        $return = DataProcessor::init()->in([10, 11, 12])->verify(13);

        $this->assertEquals(false, $return);
    }

    public function testInTrueWithErrors(){

        $return = DataProcessor::init()->in([10, 11, 12])->verify(10, Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testInFalseWithErrors(){
        try {
            $return = DataProcessor::init()->in([10, 11, 12])->verify(13, Errors::ALL);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('in'), $e->getErrors()['in']);}
    }
}
