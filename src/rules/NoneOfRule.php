<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-09
 * Time: 12:42 AM
 */

namespace Processor\Rules;


use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\AbstractRule;

class NoneOfRule extends AbstractRule
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

            if($return){
                break;
            }
            self::$data = $oldData;
        }

        return !$return;
    }

    public function processWithErrors()
    {
        $success = false;
        $this->rule();

        $errors = [];
        /* @var DataProcessor $proc */
        foreach($this->processors as $proc){
            try{
                $oldData = self::$data;
                $proc->setNameForErrors($this->nameForErrors);
                $proc->processWithErrors();
                $success = true;
                $errors[] = $proc->getMockedErrors();
            } catch(FailedProcessingException $e){
                self::$data = $oldData;
            }
        }
        if($success){
            $this->returnErrors[] = $this->getActualErrorMessage();
            foreach ($errors as $error) {
                $this->returnErrors[] = $error;
            }
            throw new FailedProcessingException($this->returnErrors);
        }

        return true;
    }

}