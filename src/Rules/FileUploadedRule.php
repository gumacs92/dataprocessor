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
        $return = is_string($pathname) && is_uploaded_file($pathname);
        return $return;
    }
}