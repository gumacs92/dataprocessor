<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 08:53 PM
 */

namespace Tests\Unit\Rules;

use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FileMoveUploadRule;
use Tests\Helpers\Tools;

class FileMoveUploadRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var FileMoveUploadRule $rule */
    public $rule;

    public function setUp()
    {
        $this->rule = new FileMoveUploadRule('asd', 'd:\Development\testdata\testto\\');

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

    public function testFileMoveUploadTrue()
    {
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(true, $return);
    }

    public function testFileMoveUploadFalse()
    {
        $_FILES['test']['tmp_name'] = '';
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(false, $return);
    }

    public function testFileMoveUploadTrueWithErrors()
    {
        $return = $this->rule->process($_FILES['test'], Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testFileMoveUploadFalseWithErrors()
    {
        $_FILES['test']['tmp_name'] = '';
        $return = $this->rule->process($_FILES['test'], Errors::ALL);

        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('fileMoveUpload'), ["minSize" => 100000, "maxSize" => 200000]), $this->rule->getResultErrors()['fileMoveUpload']);
        $this->assertEquals(false, $return);
    }
}
