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

class EachRule extends AbstractRule
{
    /* @var DataProcessor $valueProcessor */
    protected $valueProcessor;
    /* @var DataProcessor $keyProcessor */
    protected $keyProcessor;


    public function process()
    {
        $this->rule();
        $keysuccess = true;
        $valuesuccess = true;
        $success = true;


        $data = self::$data;

        foreach ($data as $key => $value) {
            if (isset($this->valueProcessor)) {
                $this->valueProcessor->setNameForErrors($this->nameForErrors);
                $valuesuccess = $this->valueProcessor->verify($value);
            }
            if (isset($this->keyProcessor)) {
                $this->keyProcessor->setNameForErrors($this->nameForErrors);
                $keysuccess = $this->valueProcessor->verify($value);
            }

            if (!$keysuccess || !$valuesuccess) {
                $success = false;
                break;
            }
        }

        self::$data = $data;

        return $success;
    }


    public
    function processWithErrors()
    {
        $this->rule();
        $keysuccess = true;
        $valuesuccess = true;
        $success = true;

        $errors = [];

        $data = self::$data;

        foreach ($data as $key => $value) {
            if (isset($this->valueProcessor)) {
                try {
                    $this->valueProcessor->setNameForErrors($this->nameForErrors);
                    $this->valueProcessor->verify($value, true);
                } catch (FailedProcessingException $e) {
                    $errors[] = $e->getAllErrors();
                    $valuesuccess = false;
                }
            }
            if (isset($this->keyProcessor)) {
                try {
                    $this->keyProcessor->setNameForErrors($this->nameForErrors);
                    $this->valueProcessor->verify($value, true);
                } catch (FailedProcessingException $e) {
                    $errors[] = $e->getAllErrors();
                    $keysuccess = false;
                }
            }

            if (!$keysuccess || !$valuesuccess) {
                $success = false;
                break;
            }
        }

        self::$data = $data;
        if (!$success) {
            $this->returnErrors[] = $this->getActualErrorMessage();
            foreach ($errors as $error) {
                $this->returnErrors[] = $error;
            }
            throw new FailedProcessingException($this->returnErrors);
        }


        return $success;
    }
}