<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 10:44 AM
 */

namespace Processor;

use Processor\Rules\Abstraction\Errors;
use Processor\Rules\Abstraction\ProcessorResult;
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

    final public function setErrorSettings()
    {
        foreach ($this->errorMessages as $ruleName => $errorMessage) {
            RuleSettings::setErrorSetting($ruleName, $errorMessage);
        }
    }

    final public function checkRequest($request, $allerrors = Errors::ONE)
    {
        foreach ($request as $field => $value) {
            if (isset($this->rules[$field])) {
                /* @var ProcessorResult $result */
                $result = $this->rules[$field]->process($value, $allerrors);

                if ($result->isSuccess()) {
                    $this->validatedData[$field] = $result->getData();
                } else {
                    $this->resultErrors[$field] = $result->getErrors();
                }
            }
        }

        return !empty($this->resultErrors) ? false : true;
    }


    /**
     * @return mixed
     */
    public
    function getValidatedData()
    {
        return $this->validatedData;
    }

    /**
     * @return array
     */
    public
    function getResultErrors()
    {
        return $this->resultErrors;
    }

}