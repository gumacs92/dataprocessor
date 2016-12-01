<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 04:12 PM
 */

namespace Processor\Rules;

use Processor\DataProcessor;
use Processor\Exceptions\FailedProcessingException;
use Processor\Rules\Abstraction\AbstractRule;

class OneOfRule extends AbstractRule
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
        $failed = true;
        $errors = [];

        /* @var DataProcessor $proc */
        foreach ($this->processors as $proc) {
            try {
                $oldData = $this->data;
                $proc->setName($this->name);
                $return = $proc->verify($this->data, $this->feedback);

                if ($return) {
                    $this->data = $proc->getData();
                    $failed = false;
                }
            } catch (FailedProcessingException $e) {
                $this->data = $oldData;
                $this->addDataProcessorErrors($e->getErrors());
            }
        }

        return $failed ? false : true;

    }
}