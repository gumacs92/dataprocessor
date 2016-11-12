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
use Processor\Rules\RuleSettings;
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
        $return = DataProcessor::init()->Each(DataProcessor::init()->FileNoError()->FileUploaded()->FileMimeType('image/jpeg')->FileSize(100000)->FileMoveUpload('asd', 'd:\Development\testdata\testto\\'))->verify($_FILES);

        $this->assertEquals(true, $return);
    }

    public function testUploadSuccessful()
    {
        $return = DataProcessor::init()->FileNoError()->FileUploaded()->FileMimeType('image/jpeg')->FileSize(100000)->FileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->verify($_FILES['test']);

        $this->assertEquals(true, $return);
    }

    public function testUploadFailureAtNoError()
    {
        $_FILES['test']['error'] = 1;
        try {
            DataProcessor::init()->FileNoError()->FileUploaded()->FileMimeType('image/jpeg')->FileSize(100000)->FileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->verify($_FILES['test'], true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = RuleSettings::getErrorSetting("fileNoError")['exceeded_default_limit'];
            $got = $e->getAllErrors()[0];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return);
        }
    }

    public function testUploadFailureAtUploaded()
    {
        $_FILES['test']['tmp_name'] = "";
        try {
            DataProcessor::init()->FileNoError()->FileUploaded()->FileMimeType('image/jpeg')->FileSize(100000)->FileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->verify($_FILES['test'], true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = RuleSettings::getErrorSetting("fileUploaded");
            $got = $e->getAllErrors()[0];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return);
        }
    }

    public function testUploadFailureAtMimeType()
    {
        try {
            DataProcessor::init()->init()->FileNoError()->FileUploaded()->FileMimeType('image/png')->FileSize(100000)->FileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->verify($_FILES['test'], true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = Tools::searchAndReplace(RuleSettings::getErrorSetting("fileMimeType"), ["mimeType" => "image/png"]);
            $got = $e->getAllErrors()[0];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return);
        }
    }

    public function testUploadFailureAtFileSize()
    {
        try {
            DataProcessor::init()->FileNoError()->FileUploaded()->FileMimeType('image/jpeg')->FileSize(300000)->FileMoveUpload('asd', 'd:\Development\testdata\testfrom\\')->verify($_FILES['test'], true);
        } catch (FailedProcessingException $e) {
            $return = false;
            $expected = Tools::searchAndReplace(RuleSettings::getErrorSetting("fileSize"), ["minSize" => 300000]);
            $got = $e->getAllErrors()[0];
            $this->assertEquals($expected, $got);
        } finally {

            $this->assertEquals(false, $return);
        }
    }
}
