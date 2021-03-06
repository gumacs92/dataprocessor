<?php

namespace Processor;


use Processor\Factory\RuleFactory;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\ProcessorResult;
use Processor\Rules\Abstraction\RuleSettings;


/**
 * Class DataProcessor
 * @package Processor
 * @method DataProcessor allOf(...$processors)
 * @method DataProcessor alnum($extraCharacters = "")
 * @method DataProcessor arrayVal()
 * @method DataProcessor between($min, $max, $inclusive = true)
 * @method DataProcessor boolType()
 * @method DataProcessor boolVal()
 * @method DataProcessor date($format = "", $convert = false)
 * @method DataProcessor dateTimeFormat($format, $locale = '')
 * @method DataProcessor digit($extraCharacters = "")
 * @method DataProcessor each($valueProcessor, $keyProcessor = null)
 * @method DataProcessor escapeHtmlTags()
 * @method DataProcessor fileMimeType($mimeType)
 * @method DataProcessor fileMoveUpload($uniqueId, $uploadPath, $uploadName = "", $extension = "", $fullPath = false, $delete = true)
 * @method DataProcessor fileNoError($size = "")
 * @method DataProcessor fileSize($minSize, $maxSize = null)
 * @method DataProcessor fileUploaded()
 * @method DataProcessor filterValidator($filter, $options = null)
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
 * @method DataProcessor phone()
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

    public static function init()
    {
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
        /* @var AbstractRule $ruleClass ; */
        $factory = new RuleFactory();
        $ruleClass = $factory->get($name, $arguments);
        $ruleClass->setName($this->name);


        return $ruleClass;
    }


    public function process($data, $feedback = Errors::NONE, $canBeEmpty = false){
        $this->data = $data;
        $this->feedback = $feedback;
        $this->canBeEmpty = $canBeEmpty;

        $result = $this->rule();

        return $result;
    }

    public function rule()
    {
        $failed = false;
        $errors = [];

        /* @var AbstractRule $rule */
        foreach ($this->ruleList as $rule) {
            $rule->setName($this->name);
            $result = $rule->process($this->data, $this->feedback);

            if($result){
                $this->data = $rule->data;
            }else{
                $failed = true;
                $this->addResultErrorSameLevel($rule->getResultErrors());
            }
        }

        $result = new ProcessorResult($this->data, !$failed, $this->getResultErrors());

        return $result;
    }

    public function getMockedErrors($mode = RuleSettings::MODE_DEFAULT)
    {
        $errors = [];
        /* @var AbstractRule $rule */
        foreach ($this->ruleList as $rule) {
            $errors[$rule->getRuleName()] = $rule->getMockedErrorMessage($mode);
        }

        if($this->feedback === Errors::ONE){
            return [key($errors) => current($errors)];
        }elseif($this->feedback === Errors::ALL){
            return $errors;
        }
        return [];
    }
}