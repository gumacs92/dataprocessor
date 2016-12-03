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
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;

class AlnumRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testAlnumTrueExtraCharacters(){
        $return = DataProcessor::init()->alnum("-")->process("123-a");

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testAlnumTrue(){
        $return = DataProcessor::init()->alnum()->process("123");

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testAlnumFalse(){
        $return = DataProcessor::init()->alnum()->process("12-a");

        $this->assertEquals(false, $return->isSuccess());
    }

    public function testAlnumTrueWithError(){
        $return = DataProcessor::init()->alnum()->process("123", Errors::ALL);

        $this->assertEquals(true, $return->isSuccess());

    }

    public function testAlnumFalseWithError(){
        try{
            $return = DataProcessor::init()->alnum()->process("123-a", Errors::ALL);
        } catch(FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("alnum"), $e->getErrors()['alnum']);
        }finally{
            $this->assertEquals(false, $return->isSuccess());
        }
    }
}
