<?php

namespace Processor;


use Processor\Factory\FactoryProducer;
use Processor\Exceptions\RuleException;
use Processor\Exceptions\FailedProcessingException;


use Processor\Factory\RuleFactory;
use Processor\Rules\AbstractRule;


/**
 * Class DataProcessor
 * @package Processor
 * @method DataProcessor Alnum($extraCharacters = "")
 * @method DataProcessor AllOf(...$processors)
 * @method DataProcessor Between($min, $max, $inclusive = true);
 * @method DataProcessor Digit($extraCharacters = "")
 * @method DataProcessor Each($valueProcessor, $keyProcessor = null)*
 * @method DataProcessor FileNoError($size = "")
 * @method DataProcessor FileMimeType($mimeType)
 * @method DataProcessor FileMoveUpload($uniqueId, $uploadPath, $uploadName = "", $extension = "", $fullPath = false, $delete = true)
 * @method DataProcessor FileSize($minSize, $maxSize = null)
 * @method DataProcessor FileUploaded()
 * @method DataProcessor FloatType()
 * @method DataProcessor IntCast()
 * @method DataProcessor In($list, $strict = false)
 * @method DataProcessor IntType()
 * @method DataProcessor Length($min, $max, $inclusive = true)
 * @method DataProcessor NoneOf(...$processors)
 * @method DataProcessor NoWhitespaces($extraCharacters = "")
 * @method DataProcessor Numeric()
 * @method DataProcessor StringType()
 * @method DataProcessor Optional(DataProcessor $rule)
 * @method DataProcessor OneOf(...$processors)
 */
class DataProcessor extends AbstractRule
{
    private $ruleList = [];

    public static function init(){
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
        $ruleClass = "";

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