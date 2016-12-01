<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 05:03 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class FileSizeRule extends AbstractRule
{
    protected $minSize;
    protected $maxSize;

    public function __construct($minSize, $maxSize = null)
    {
        parent::__construct();

        $this->minSize = $this->typeCheck($minSize, 'integer');
        $this->maxSize = $this->typeCheck($maxSize, 'integer');
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

        $size = $tmpfile->getSize();

        if ((!is_null($this->minSize) and $size < $this->minSize) or
            (!is_null($this->maxSize) and $size > $this->maxSize) ){
            return false;
        }

        return true;

    }
}