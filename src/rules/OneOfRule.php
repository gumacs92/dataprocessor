<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 04:12 PM
 */

namespace Processor\Rules;


use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\AbstractRule;
use Processor\DataProcessor;

class OneOfRule extends AbstractRule
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

        return $return;
    }

    public function processWithErrors()
    {
        $return = false;
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
                break;
            } catch(FailedProcessingException $e){
                self::$data = $oldData;
                $errors[] = $e->getAllErrors();
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