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
use Processor\Rules\Abstraction\AbstractRule;

class NoneOfRule extends AbstractRule
{
    protected $processors = [];

    public function __construct(...$processors)
    {
        parent::__construct();
        if (is_array($processors)) {
            foreach ($processors as $proc) {
                $this->typeCheck($proc, DataProcessor::class);
            }
            $this->processors = $processors;
        } else {
            $this->processors[] = $this->typeCheck($processors, DataProcessor::class);
        }

    }

    public function rule()
    {
        $failed = false;
        $errors = [];

        /* @var DataProcessor $proc */
        foreach ($this->processors as $proc) {
            try {
                $oldData = $this->data;
                $proc->setName($this->name);
                $return = $proc->verify($this->data, $this->feedback);

                if ($return) {
                    $failed = true;
                    $this->data = $oldData;
//                    $errors[] = $proc->getMockedErrors();
                    $this->addReturnErrors($proc->getMockedErrors());
                }
            } catch (FailedProcessingException $e) {
                $this->addReturnErrors($e->getErrors());
                $this->data = $oldData;
            }
        }

        return $failed ? false : true;
    }

}