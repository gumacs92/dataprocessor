<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 02:27 AM
 */

namespace Processor\Rules;

use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\AbstractRule;

class EachRule extends AbstractRule
{
    /* @var DataProcessor $valueProcessor */
    protected $valueProcessor;
    /* @var DataProcessor $keyProcessor */
    protected $keyProcessor;

    public function __construct($valueProcessor, $keyProcessor = null)
    {
        parent::__construct();
        $this->valueProcessor = $this->typeCheck($valueProcessor, DataProcessor::class);
        $this->keyProcessor = $this->typeCheck($keyProcessor, DataProcessor::class);
    }

    public function rule()
    {


        $keysuccess = true;
        $valuesuccess = true;
        $success = true;
        $errors = [];


        foreach ($this->data as $key => $value) {
            if (isset($this->valueProcessor)) {
                try {
                    $oldData = $value;
                    $this->valueProcessor->setName($this->name);
                    $valuesuccess = $this->valueProcessor->verify($value, $this->feedback);
                    $this->data = $this->valueProcessor->getData();
                } catch (FailedProcessingException $e) {
                    $valuesuccess = false;
                    $this->addReturnErrors($e->getErrors());
                    $value = $oldData;
                }
            }
            if (isset($this->keyProcessor)) {
                try {
                    $oldData = $key;
                    $this->keyProcessor->setName($this->name);
                    $keysuccess = $this->keyProcessor->verify($key, $this->feedback);
                    $this->data = $this->keyProcessor->getData();
                } catch (FailedProcessingException $e) {
                    $keysuccess = false;
                    $this->addReturnErrors($e->getErrors());
                    $key = $oldData;
                }
            }

            if (!$keysuccess || !$valuesuccess) {
                $success = false;
                break;
            }
        }

        return $success;
    }
}