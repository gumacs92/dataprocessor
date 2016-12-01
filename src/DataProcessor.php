<?php

namespace Processor;


use Processor\Exceptions\FailedProcessingException;
use Processor\Factory\RuleFactory;
use Processor\Rules\Abstraction\AbstractRule;
use Processor\Rules\Abstraction\Errors;


/**
 * Class DataProcessor
 * @package Processor
 * @method DataProcessor allOf(...$processors)
 * @method DataProcessor alnum($extraCharacters = "")
 * @method DataProcessor arrayVal()
 * @method DataProcessor between($min, $max, $inclusive = true);
 * @method DataProcessor boolType()
 * @method DataProcessor boolVal()
 * @method DataProcessor date($format = "", $convert = false)
 * @method DataProcessor dateFormat($format)
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
    private static $returnData;

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

        return $ruleClass;
    }


    public function verify($data, $feedback = Errors::NONE){
        $this->data = $data;
        $this->feedback = $feedback;

        $this->rule();

        $errors = $this->getReturnErrors();

        //TODO better error process but the logic is here already
        if(empty($errors)){
            self::$returnData = $this->data;
            return true;
        } else {
            switch ($feedback){
                case Errors::NONE:
                    return false;
                    break;
                case Errors::ONE:
                    throw new FailedProcessingException([key($errors) => current($errors)]);
                    break;
                case Errors::ALL:
                    throw new FailedProcessingException($errors);
                    break;
                default:
                    return false;
                    break;
            }
        }

    }

    public function rule()
    {
        /* @var AbstractRule $rule */
        foreach ($this->ruleList as $rule) {
            $rule->setName($this->name);
            $result = $rule->process($this->data, $this->feedback);

            if($result){
                $this->data = $rule->getData();
            }else{
                $error = $rule->getReturnErrors();
                $this->returnErrors[key($error)] = current($error);
            }
        }
    }

    public function getMockedErrors()
    {
        $errors = [];
        /* @var AbstractRule $rule */
        foreach ($this->ruleList as $rule) {
            $errors[$rule->ruleName] = $rule->getMockedErrorMessage();
        }

        if($this->feedback === Errors::ONE){
            return [key($errors) => current($errors)];
        }elseif($this->feedback === Errors::ALL){
            return $errors;
        }
        return [];
    }

    /**
     * @return mixed
     */
    public static function getReturnData()
    {
        return self::$returnData;
    }



}