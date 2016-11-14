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

    public function rule()
    {
        parent::rule();

        if (isset(self::$data['tmp_name'])) {

            $tmpfile = new \SplFileInfo(self::$data['tmp_name']);

        } elseif (is_string(self::$data)) {
            $tmpfile = new \SplFileInfo(self::$data);
        } elseif (self::$data instanceof \SplFileInfo) {
            $tmpfile = self::$data;
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