<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-12-02
 * Time: 07:31 PM
 */

namespace Processor\Rules\Abstraction;


class ProcessorResult
{
    private $data;
    private $success;
    private $errors;

    public function __construct($data, $success, $errors)
    {
        $this->data = $data;
        $this->success = $success;
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }


}