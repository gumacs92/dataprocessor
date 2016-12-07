<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 02:27 AM
 */

namespace Processor\Rules;

use Processor\DataProcessor;
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


        foreach ($this->data as $key => $value) {
            if (isset($this->valueProcessor)) {
                $this->valueProcessor->setName($this->name);
                $result = $this->valueProcessor->process($value, $this->feedback);

                if (!($valuesuccess = $result->isSuccess())) {
                    $this->addResultErrorNewLevel($result->getErrors());
                } else {
                    $this->data[$key] = $result->getData();
                }
            }
            if (isset($this->keyProcessor)) {
                $this->keyProcessor->setName($this->name);
                $result = $this->keyProcessor->process($key, $this->feedback);

                if (!($keysuccess = $result->isSuccess())) {
                    $this->addResultErrorNewLevel($result->getErrors());
                } else {
                    $this->data[$result->getData()] = $this->data[$key];
                    unset($this->data[$key]);
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