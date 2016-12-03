<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 08:48 PM
 */

namespace Tests\Unit\Rules\Validators;


use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Processor\Rules\FileSizeRule;
use Tests\Helpers\Tools;

class FileSizeRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var FileSizeRule $rule */
    public $rule;

    public function setUp()
    {
        $this->rule = new FileSizeRule(100000, 200000);

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

    public function testFileSizeTrue()
    {
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(true, $return);
    }

    public function testFileSizeFalse()
    {
        $_FILES['test']['tmp_name'] = 'd:\Development\testdata\testfrom\test.php';
        $return = $this->rule->process($_FILES['test']);

        $this->assertEquals(false, $return);
    }

    public function testFileSizeTrueWithErrors()
    {
        $return = $this->rule->process($_FILES['test'], Errors::ALL);

        $this->assertEquals(true, $return);
    }

    public function testFileSizeFalseWithErrors()
    {
        $_FILES['test']['tmp_name'] = 'd:\Development\testdata\testfrom\test.php';

        $return = $this->rule->process($_FILES['test'], Errors::ALL);

        $this->rule->getMockedErrorMessage();
        $this->assertEquals(Tools::searchAndReplace(RuleSettings::getErrorSetting('fileSize', RuleSettings::MODE_DEFAULT), ["minSize" => 100000, "maxSize" => 200000]), $this->rule->getMockedErrorMessage());

        $this->assertEquals(false, $return);
    }
}
