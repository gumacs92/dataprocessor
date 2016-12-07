<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 04:12 PM
 */

namespace Processor\Rules;

use Processor\DataProcessor;
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
        $success = false;
        $errors = [];

        /* @var DataProcessor $proc */
        foreach ($this->processors as $proc) {
            $proc->setName($this->name);
            $result = $proc->process($this->data, $this->feedback);

            if ($result->isSuccess()) {
                $success = true;
                $this->data = $result->getData();
            } else {
                $this->addResultErrorNewLevel($result->getErrors());
            }
        }

        return $success;
    }
}