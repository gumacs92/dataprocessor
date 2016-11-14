<?php

namespace Processor;


use Processor\Exceptions\FailedProcessingException;
use Processor\Exceptions\RuleException;
use Processor\Factory\RuleFactory;
use Processor\Rules\AbstractRule;


/**
 * Class DataProcessor
 * @package Processor
 * @method DataProcessor allOf(...$processors)
 * @method DataProcessor alnum($extraCharacters = "")
 * @method DataProcessor arrayVal()
 * @method DataProcessor between($min, $max, $inclusive = true);
 * @method DataProcessor boolType()
 * @method DataProcessor boolVal()
 * @method DataProcessor digit($extraCharacters = "")
 * @method DataProcessor each($valueProcessor, $keyProcessor = null)
 * @method DataProcessor escapeHtmlTags()
 * @method DataProcessor fileMimeType($mimeType)
 * @method DataProcessor fileMoveUpload($uniqueId, $uploadPath, $uploadName = "", $extension = "", $fullPath = false, $delete = true)
 * @method DataProcessor fileNoError($size = "")
 * @method DataProcessor fileSize($minSize, $maxSize = null)
 * @method DataProcessor fileUploaded()
 * @method DataProcessor floatType()
 * @method DataProcessor floatVal()
 * @method DataProcessor in($list, $strict = false)
 * @method DataProcessor intType()
 * @method DataProcessor intVal()
 * @method DataProcessor length($min, $max, $inclusive = true)
 * @method DataProcessor noneOf(...$processors)
 * @method DataProcessor noWhitespaces($extraCharacters = "")
 * @method DataProcessor numeric()
 * @method DataProcessor oneOf(...$processors)
 * @method DataProcessor optional(DataProcessor $rule)
 * @method DataProcessor removeHtmlTags(\HTMLPurifier_Config $config = null)
 * @method DataProcessor setTypeBool()
 * @method DataProcessor setTypeFloat()
 * @method DataProcessor setTypeInt()
 * @method DataProcessor setTypeString()
 * @method DataProcessor stringType()
 * @method DataProcessor unicodeAlnum($extraCharacters = "")
 */
class DataProcessor extends AbstractRule
{
    private $ruleList = [];

    public static function init(){
        if (!defined('DS')) {
            define("DS", DIRECTORY_SEPARATOR);
        }
        if (!defined('ROOT')) {
            define('ROOT', dirname(getcwd(), 1) . DS);
        }
        if (!defined('VENDOR_PATH')) {
            define('VENDOR_PATH', ROOT . 'vendor' . DS);
        }

        require_once VENDOR_PATH . "autoload.php";

        $analyser = new DataProcessor();

        return $analyser;
    }

    public static function __callStatic($name, $arguments)
    {
        $analyser = new DataProcessor();

        $analyser->ruleList[] = $analyser->searchRule($name, $arguments);

        return $analyser;
    }

    public function __call($name, $arguments)
    {
        $this->ruleList[] = $this->searchRule($name, $arguments);

        return $this;
    }

    public function searchRule($name, $arguments)
    {
        /* @var AbstractRule $ruleClass; */
        $ruleClass = $this->retrieveRule($name);
        $ruleClass->checkArguments($arguments);

        return $ruleClass;
    }

    public function retrieveRule($name)
    {
        $factory = new RuleFactory();

        $ruleClass = $factory->get($name);

        return $ruleClass;
    }

    public function rule()
    {
    }

    public function process()
    {
        $failed = false;

        /* @var AbstractRule $rule */
        foreach ($this->ruleList as $rule) {
            $rule->setNameForErrors($this->nameForErrors);
            $result = $rule->process();

            if(!$result){
                $failed = true;
                break;
            }
        }

        return $failed ? false : true;
    }

    public function processWithErrors()
    {
        $failed = false;

        /* @var AbstractRule $rule */
        foreach ($this->ruleList as $rule) {
            $rule->setNameForErrors($this->nameForErrors);
            if(!$failed){
                try {
                    $rule->processWithErrors();

                } catch (RuleException $e) {
                    $failed = true;
                    $this->returnErrors[] = $e->getErrorMessage();
                }
            } else{
                $this->returnErrors[] = $rule->getActualErrorMessage();
            }

        }
        if($failed){
            throw new FailedProcessingException($this->returnErrors);
        }
        return true;
    }

    public function getMockedErrors(){
        $errors = [];
        /* @var AbstractRule $rule */
        foreach($this->ruleList as $rule){
            $errors[] = $rule->getActualErrorMessage();
        }
        return $errors;
    }

}