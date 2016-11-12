<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 07:31 PM
 */

namespace Tests\Unit\Rules\Validators;



use Processor\Exceptions\RuleException;

use Processor\Rules\RuleSettings;
use Processor\Rules\FileNoErrorRule;

class FileNoErrorRuleTest extends \PHPUnit_Framework_TestCase
{
    /* @var FileNoErrorRule $rule */
    public $rule;

    public function setUp()
    {
        $this->rule = new FileNoErrorRule();
        $this->rule->setRuleName("fileNoError");

        $_FILES = array(
            'test' => array(
                'name' => 'test.jpg',
                'type' => 'image/jpeg',
                'size' => 542,
                'tmp_name' => getcwd() . '/helper/testfrom/test.jpg',
                'error' => 0
            )
        );
    }

    public function testFileNoErrorTrue(){
        $return = $this->rule->verify($_FILES['test']);

        $this->assertEquals(true, $return);
    }

    public function testFileNoErrorFalse(){
        $_FILES['test']['error'] = 2;
        $return = $this->rule->verify($_FILES['test']);

        $this->assertEquals(false, $return);
    }

    public function testFileNoErrorTrueWithErrors(){
        $return = $this->rule->verify($_FILES['test'], true);

        $this->assertEquals(true, $return);
    }

    public function testFileNoErrorFalseWithErrors(){
        $_FILES['test']['error'] = 2;
        try{
            $this->rule->verify($_FILES['test'], true);
        } catch (RuleException $e){
            $return = false;
            $this->assertEquals(RuleSettings::getErrorSetting('fileNoError')['exceeded_form_limit'], $e->getErrorMessage());
        } finally {

            $this->assertEquals(false, $return);
        }

    }
}
