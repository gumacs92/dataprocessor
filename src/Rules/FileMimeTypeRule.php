<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 04:41 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class FileMimeTypeRule extends AbstractRule
{
    protected $mimeType;

    public function __construct($mimeType)
    {
        parent::__construct();

        $this->mimeType = $this->typeCheck($mimeType, 'string');
    }

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

        if (!is_string($pathname) || !is_file($pathname)) {
            return false;
        }

        $pathname = $tmpfile->getPathname();
        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        $return = $finfo->file($pathname) == $this->mimeType;
        return $return;
    }
}