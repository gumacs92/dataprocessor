<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 04:09 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class FileUploadedRule extends AbstractRule
{
    public function rule()
    {
        if (isset($this->data['tmp_name'])) {
            $tmpfile = new \SplFileInfo($this->data['tmp_name']);

        } elseif (is_string($this->data)) {
            $tmpfile = new \SplFileInfo($this->data);
        } elseif ($this->data instanceof \SplFileInfo) {
            $tmpfile = $this->data;
        } else {
            return false;
        }

        $pathname = $tmpfile->getPathname();
        $return = is_string($pathname) && is_uploaded_file($pathname);
        return $return;
    }
}