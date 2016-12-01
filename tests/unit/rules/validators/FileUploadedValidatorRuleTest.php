<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 08:04 PM
 */

namespace Tests\Unit\Rules\Validators;

use Processor\Exceptions\RuleException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FileUploadedRule;


class FileUploadedRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var FileUploadedRule $rule */
    public $rule;

    public function setUp()
    {
        $this->rule = new FileUploadedRule();

        $_FILES = array(
            'test' => array(
                'name' => 'test.jpg',
                'type' => 'image/jpeg',
                'size' => 542,
                'tmp_name' => 'd:\Development\testdata\testfrom\test.jpg',
                'error' => 0
            )
        );
    }

    public function testFileUploadedTrue(){
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(true, $return);
    }

    public function testFileUploadedFalse(){
        $_FILES['test']['tmp_name'] = "";
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(false, $return);
    }

    public function testFileUploadedTrueWithErrors(){
        $return = $this->rule->process($_FILES['test'], Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testFileUploadedFalseWithErrors(){
        $_FILES['test']['tmp_name'] = "";
        try{
            $return = $this->rule->process($_FILES['test'], Errors::ALL);
        } catch (RuleException $e){
            $return = false;
            $this->assertEquals(RuleSettings::getErrorSetting('fileUploaded'), $e->getErrorMessage());
        } finally {

            $this->assertEquals(false, $return);
        }

    }
}

