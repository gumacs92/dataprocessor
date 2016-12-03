<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 10:44 AM
 */

namespace Processor;

use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\RuleSettings;

abstract class RequestProcessor
{
    protected $rules = [];
    protected $errorMessages = [];

    protected $resultErrors = [];
    private $validatedData;

    public function __construct()
    {
        $this->initErrorSettings();
        $this->initRules();

        $this->setErrorSettings();
    }

    abstract public function initErrorSettings();

    abstract public function initRules();

    final public function setErrorSettings(){
        foreach ($this->errorMessages as $ruleName => $errorMessage) {
            RuleSettings::setErrorSetting($ruleName, $errorMessage);
        }
    }

    final public function checkRequest($request, $allerrors = true)
    {
        $failed = false;
        foreach ($request as $field => $value) {
            try {
                if (isset($this->rules[$field])) {
                    $this->rules[$field]->process($value, $allerrors);

                    $this->validatedData[$field] = DataProcessor::getReturnData();
                }
            } catch (FailedProcessingException $e) {
                $failed = true;
                if($allerrors){
                    $this->resultErrors[$field] = $e->getErrors();
                }
            }
        }

        return $failed ? false : true;
    }


    /**
     * @return mixed
     */
    public function getValidatedData()
    {
        return $this->validatedData;
    }

    /**
     * @return array
     */
    public function getResultErrors()
    {
        return $this->resultErrors;
    }

}