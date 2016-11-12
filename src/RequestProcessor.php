<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 10:44 AM
 */

namespace Processor;

use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\AbstractRule;
use Processor\Rules\RuleSettings;

abstract class RequestProcessor
{
    protected $rules = [];
    protected $newErrorMessages = [];

    protected $errors = [];
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
        foreach ($this->newErrorMessages as $ruleName => $errorMessage){
            RuleSettings::setErrorSetting($ruleName, $errorMessage);
        }
    }

    final public function checkRequest($request, $allerrors = true)
    {
        $failed = false;
        foreach ($request as $field => $value) {
            try {
                /* @var AbstractRule $this->rules[] */
                if (isset($this->rules[$field])) {
                    $value = $this->rules[$field]($value);
                    $this->rules[$field]->verify($value, $allerrors);

                    $this->validatedData[$field] = $this->rules->getData();
                }
            } catch (FailedProcessingException $e) {
                $failed = true;
                if($allerrors){
                    $this->errors[$field] = $e->getAllErrors();
                }
            }
        }

        return $failed ? false : true;
    }

}