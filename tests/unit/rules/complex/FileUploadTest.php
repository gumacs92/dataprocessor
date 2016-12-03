<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-11
 * Time: 01:31 AM
 */

namespace Processor\Rules;

function move_uploaded_file($name, $name2)
{
    return file_exists($name);
}

namespace Processor\Rules;

function is_uploaded_file($name)
{
    return file_exists($name);
}


namespace Tests\Unit\Rules;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\RuleSettings;
use Tests\Helpers\Tools;

class FileUploadTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_FILES = array(
            'test' => array(
                'name' => 'test.jpg',
                'type' => 'image/jpeg',
                'size' => 542,
                'tmp_name' => 'd:\Development\testdata\testfrom\test.jpg',
                'error' => 0
            ),
            'test1' => array(
                'name' => 'test1.jpg',
                'type' => 'image/jpeg',
                'size' => 542,
                'tmp_name' => 'd:\Development\testdata\testfrom\test1.jpg',
                'error' => 0
            ),
            'test2' => array(
                'name' => 'test2.jpg',
                'type' => 'image/jpeg',
                'size' => 542,
                'tmp_name' => 'd:\Development\testdata\testfrom\test2.jpg',
                'error' => 0
            )
        );
    }

    public function testMultipleFileUploadSuccessful()
    {
        $return = DataProcessor::init()->each(DataProcessor::init()->fileNoError()->fileUploaded()->fileMimeType('image/jpeg')->fileSize(100000)->fileMoveUpload('asd', 'd:\Development\testdata\testto\\'))->process($_FILES);

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testUploadSuccessful()
    {
        $return = DataProcessor::init()->fileNoError()->fileUploaded()->fileMimeType('image/jpeg')->fileSize(100000)->fileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->process($_FILES['test']);

        $this->assertEquals(true, $return->isSuccess());
    }

    public function testUploadFailureAtNoError()
    {
        $_FILES['test']['error'] = 1;
        try {
            $return = DataProcessor::init()->fileNoError()->fileUploaded()->fileMimeType('image/jpeg')->fileSize(100000)->fileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->process($_FILES['test'], Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = RuleSettings::getErrorSetting("fileNoError")['exceeded_default_limit'];
            $got = $e->getErrors()["fileNoError"];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return->isSuccess());
        }
    }

    public function testUploadFailureAtUploaded()
    {
        $_FILES['test']['tmp_name'] = "";
        try {
            $return = DataProcessor::init()->fileNoError()->fileUploaded()->fileMimeType('image/jpeg')->fileSize(100000)->fileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->process($_FILES['test'], Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = RuleSettings::getErrorSetting("fileUploaded");
            $got = $e->getErrors()["fileUploaded"];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return->isSuccess());
        }
    }

    public function testUploadFailureAtMimeType()
    {
        try {
            $return = DataProcessor::init()->init()->fileNoError()->fileUploaded()->fileMimeType('image/png')->fileSize(100000)->fileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->process($_FILES['test'], Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = Tools::searchAndReplace(RuleSettings::getErrorSetting("fileMimeType"), ["mimeType" => "image/png"]);
            $got = $e->getErrors()["fileMimeType"];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return->isSuccess());
        }
    }

    public function testUploadFailureAtFileSize()
    {
        try {
            $return = DataProcessor::init()->fileNoError()->fileUploaded()->fileMimeType('image/jpeg')->fileSize(300000)->fileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->process($_FILES['test'], Errors::ALL);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = Tools::searchAndReplace(RuleSettings::getErrorSetting("fileSize"), ["minSize" => 300000]);
            $got = $e->getErrors()["fileSize"];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return->isSuccess());
        }
    }
}
