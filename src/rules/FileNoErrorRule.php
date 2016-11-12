<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 03:32 PM
 */

namespace Processor\Rules;


use Processor\Exceptions\InvalidArgumentException;
use Processor\Rules\AbstractRule;

class FileNoErrorRule extends AbstractRule
{
    protected $size = "";

    public function rule()
    {
        parent::rule();

        if(isset(self::$data['error'])){

        } else {$this->returnErrors['invalid_value_type_error'] = 'Fatal error: expected _FILES["uploaded_file"] type for the processed value but got ' . gettype(self::$data) .
            ' in the rule ' . get_class($this);
            throw new InvalidArgumentException($this->returnErrors['invalid_value_type_error']);

        }
        $file = self::$data;

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                return true;
            case UPLOAD_ERR_INI_SIZE:
                $this->error = 'exceeded_default_limit';
                return false;
            case UPLOAD_ERR_FORM_SIZE:
                $this->error = 'exceeded_form_limit';
                return false;
            case UPLOAD_ERR_PARTIAL:
                $this->error = 'partial_upload';
                return false;
            case UPLOAD_ERR_NO_FILE:
                $this->error = 'no_upload';
                return false;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->error = 'missing_tmp';
                return false;
            case UPLOAD_ERR_CANT_WRITE:
                $this->error = 'failed_to_write';
                return false;
            case UPLOAD_ERR_EXTENSION:
                $this->error = 'php_stopped_upload';
                return false;
            default:
                $this->error = 'unknown';
                return false;
        }
    }
}