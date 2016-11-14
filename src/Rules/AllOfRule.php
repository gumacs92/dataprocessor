<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 05:58 PM
 */

namespace Processor\Rules;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;

class AllOfRule extends AbstractRule
{
    protected $processors = [];

    public function process()
    {
        $return = false;
        $this->rule();

        /* @var DataProcessor $proc */
        foreach($this->processors as $proc){
            $oldData = self::$data;
            $proc->setNameForErrors($this->nameForErrors);
            $return = $proc->process();

            if(!$return){
                self::$data = $oldData;
                break;
            }
        }

        return $return;
    }

    public function processWithErrors()
    {
        $success = true;
        $this->rule();

        $errors = [];
        /* @var DataProcessor $proc */
        foreach($this->processors as $proc){
            try{
                $oldData = self::$data;
                $proc->setNameForErrors($this->nameForErrors);
                $proc->processWithErrors();
            } catch(FailedProcessingException $e){
                $success = false;
                $errors[] = $e->getAllErrors();
                self::$data = $oldData;
            }
        }
        if(!$success){
            $this->returnErrors[] = $this->getActualErrorMessage();
            foreach ($errors as $error) {
                $this->returnErrors[] = $error;
            }
            throw new FailedProcessingException($this->returnErrors);
        }

        return true;
    }

}