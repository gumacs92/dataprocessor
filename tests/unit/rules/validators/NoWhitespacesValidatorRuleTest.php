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
use Processor\Rules\Abstraction\RuleSettings;

class NoWhitespacesRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testNoWhitespacesFalseExtraCharacters(){
        $return = DataProcessor::init()->noWhitespaces("a")->verify("123a");

        $this->assertEquals(false, $return);
    }

    public function testNoWhitespacesTrue(){
        $return = DataProcessor::init()->noWhitespaces()->verify("123");

        $this->assertEquals(true, $return);
    }

    public function testNoWhitespacesFalse(){
        $return = DataProcessor::init()->noWhitespaces()->verify("12a ");

        $this->assertEquals(false, $return);
    }

    public function testNoWhitespacesTrueWithError(){
        $return = DataProcessor::init()->noWhitespaces()->verify("123", true);

        $this->assertEquals(true, $return);

    }

    public function testNoWhitespacesFalseWithError(){
        try{
            DataProcessor::init()->noWhitespaces()->verify("12a ", true);
        } catch(FailedProcessingException $e) {
            $return = false;
            $this->assertEquals(1, sizeof($e->getAllErrors()));
            $this->assertEquals(RuleSettings::getErrorSetting("noWhitespaces"), $e->getAllErrors()[0]);
        }finally{
            $this->assertEquals(false, $return);
        }
    }
}
