<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 08:32 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\RuleSettings;

class AlnumRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testAlnumTrueExtraCharacters(){
        $return = DataProcessor::init()->alnum("-")->verify("123-a");

        $this->assertEquals(true, $return);
    }

    public function testAlnumTrue(){
        $return = DataProcessor::init()->alnum()->verify("123");

        $this->assertEquals(true, $return);
    }

    public function testAlnumFalse(){
        $return = DataProcessor::init()->alnum()->verify("12-a");

        $this->assertEquals(false, $return);
    }

    public function testAlnumTrueWithError(){
        $return = DataProcessor::init()->alnum()->verify("123", true);

        $this->assertEquals(true, $return);

    }

    public function testAlnumFalseWithError(){
        try{
            DataProcessor::init()->alnum()->verify("123-a", true);
        } catch(FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("alnum"), $e->getAllErrors()[0]);
        }finally{
            $this->assertEquals(false, $return);
        }
    }
}
