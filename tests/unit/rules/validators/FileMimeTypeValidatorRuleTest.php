<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 08:38 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Exceptions\RuleException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FileMimeTypeRule;
use Tests\Helpers\Tools;

class FileMimeTypeRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var FileMimeTypeRule $rule */
    public $rule;

    public function setUp()
    {
        $this->rule = new FileMimeTypeRule('image/jpeg');

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

    public function testFileMimeTypeTrue(){
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(true, $return);
    }

    public function testFileMimeTypeFalse(){
        $_FILES['test']['tmp_name'] = 'd:\Development\testdata\testfrom\test.php';
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(false, $return);
    }

    public function testFileMimeTypeTrueWithErrors(){
        $return = $this->rule->process($_FILES['test'], Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testFileMimeTypeFalseWithErrors(){
        $_FILES['test']['tmp_name'] = 'd:\Development\testdata\testfrom\test.php';
        try{
            $return = $this->rule->process($_FILES['test'], Errors::ALL);
        } catch (RuleException $e){
            $return = false;
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('fileMimeType'), ["mimeType" => "image/jpeg"]), $e->getErrorMessage());
        } finally {

            $this->assertEquals(false, $return);
        }

    }
}
