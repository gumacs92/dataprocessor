<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 08:53 PM
 */

namespace Tests\Unit\Rules;

use Processor\Exceptions\RuleException;
use Processor\Rules\FileMoveUploadRule;
use Processor\Rules\RuleSettings;
use Tests\Helpers\Tools;

class FileMoveUploadRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var FileMoveUploadRule $rule */
    public $rule;

    public function setUp()
    {
        $this->rule = new FileMoveUploadRule();
        $this->rule->setRuleName("fileMoveUpload");
        $this->rule->checkArguments(['asd', 'd:\Development\testdata\testto\\']);

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

    public function testFileMoveUploadTrue(){
        $return = $this->rule->verify($_FILES['test']);

        $this->assertEquals(true, $return);
    }

    public function testFileMoveUploadFalse(){
        $_FILES['test']['tmp_name'] =  '';
        $return = $this->rule->verify($_FILES['test']);

        $this->assertEquals(false, $return);
    }

    public function testFileMoveUploadTrueWithErrors(){
        $return = $this->rule->verify($_FILES['test'], true);

        $this->assertEquals(true, $return);
    }

    public function testFileMoveUploadFalseWithErrors(){
        $_FILES['test']['tmp_name'] =  '';
        try{
            $this->rule->verify($_FILES['test'], true);
        } catch (RuleException $e){
            $return = false;
            $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('fileMoveUpload'), ["minSize" => 100000, "maxSize" => 200000]), $e->getErrorMessage());
        } finally {

            $this->assertEquals(false, $return);
        }

    }
}
