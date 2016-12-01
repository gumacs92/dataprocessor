<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-06
 * Time: 11:35 AM
 */

namespace Processor\Exceptions;


class FailedProcessingException extends \Exception
{
    private $errors = [];

    public function __construct($errors, $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors()
    {
        if (!empty($this->errors)) {
            return $this->errors;
        }
        return null;
    }

}