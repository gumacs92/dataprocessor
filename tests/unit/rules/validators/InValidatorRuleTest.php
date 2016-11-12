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
use Processor\Rules\RuleSettings;

class InRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testInTrue(){
        $return = DataProcessor::init()->In([10,11,12])->verify(10);

        $this->assertEquals(true, $return);
    }

    public function testInFalse(){
        $return = DataProcessor::init()->In([10,11,12])->verify(13);

        $this->assertEquals(false, $return);
    }

    public function testInTrueWithErrors(){

        $return = DataProcessor::init()->In([10,11,12])->verify(10, true);

        $this->assertEquals(true, $return);
    }

    public function testInFalseWithErrors(){
        try {
            $return = DataProcessor::init()->In([10,11,12])->verify(13, true);
        } catch (FailedProcessingException $e) {

            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting('in'), $e->getOneError());}
    }
}
