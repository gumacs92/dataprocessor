<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 08:30 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;

class NoWhitespacesRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testNoWhitespacesFalseExtraCharacters(){
        $return = DataProcessor::init()->noWhitespaces("a")->process("123a");

        $this->assertEquals(false, $return->isSuccess());
    }

    public function testNoWhitespacesTrue(){
        $return = DataProcessor::init()->noWhitespaces()->process("123");

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testNoWhitespacesFalse(){
        $return = DataProcessor::init()->noWhitespaces()->process("12a ");

        $this->assertEquals(false, $return->isSuccess());
    }

    public function testNoWhitespacesTrueWithError(){
        $return = DataProcessor::init()->noWhitespaces()->process("123", Errors::ALL);

        $this->assertEquals(true, $return->isSuccess());

    }

    public function testNoWhitespacesFalseWithError(){
        try{
            $return = DataProcessor::init()->noWhitespaces()->process("12a ", Errors::ALL);
        } catch(FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("noWhitespaces"), $e->getErrors()["noWhitespaces"]);
        }finally{
            $this->assertEquals(false, $return->isSuccess());
        }
    }
}
