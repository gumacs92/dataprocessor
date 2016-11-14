<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-10
 * Time: 05:23 PM
 */

namespace Processor\Rules;

use Mimey\MimeTypes;
use Processor\Rules\Abstraction\AbstractRule;

class FileMoveUploadRule extends AbstractRule
{
    protected $uniqueId;
    protected $uploadPath;

    protected $uploadName;
    protected $extension;
    protected $fullPath;
    protected $delete;

    public function rule()
    {
        parent::rule();

        if (isset(self::$data['tmp_name']) && isset(self::$data['name'])) {
            $tmpfile = new \SplFileInfo(self::$data['tmp_name']);
            $file = new \SplFileInfo(self::$data['name']);

            $pathname = $tmpfile->getPathname();

            if (!is_string($pathname) || !is_file($pathname)) {
                return false;
            }

            if (!isset($this->extension)) {
                $this->extension = $file->getExtension();
            }

            if (isset($this->uploadName)) {
                $this->uploadName = sha1($this->uniqueId . "_" . $this->uploadName);
            } else {
                $this->uploadName = sha1($this->uniqueId . "_" . $file->getBasename('.' . $this->extension));
            }

        } elseif (is_string(self::$data) && is_file(self::$data)) {
            $tmpfile = new \SplFileInfo(self::$data);

            $pathname = $tmpfile->getPathname();

            if (!is_string($pathname) || !is_file($pathname)) {
                return false;
            }

            if (!isset($this->extension)) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimy = new MimeTypes();
                $this->extension = $mimy->getExtension($finfo->file($pathname));
            }

            if (isset($this->uploadName)) {
                $this->uploadName = sha1($this->uniqueId . "_" . $this->uploadName);
            } else {
                $ext = $tmpfile->getExtension();
                $this->uploadName = sha1($this->uniqueId . "_" . $tmpfile->getBasename('.' . $ext));
            }
        } elseif (self::$data instanceof \SplFileInfo) {
            /* @var \SplFileInfo $tmpfile */
            $tmpfile = self::$data;

            $pathname = $tmpfile->getPathname();

            if (!is_string($pathname) || !is_file($pathname)) {
                return false;
            }

            if (!isset($this->extension)) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimy = new MimeTypes();
                $this->extension = $mimy->getExtension($finfo->file($pathname));
            }

            if (isset($this->uploadName)) {
                $this->uploadName = sha1($this->uniqueId . "_" . $this->uploadName);
            } else {
                $ext = $tmpfile->getExtension();
                $this->uploadName = sha1($this->uniqueId . "_" . $tmpfile->getBasename('.' . $ext));
            }
        } else {
            return false;
        }

        $pathname = $tmpfile->getPathname();

        $savename = $this->uploadName . "." . $this->extension;
        $uploadhere = $this->uploadPath . $savename;

        if ($this->delete && file_exists($uploadhere)) {
            unlink($uploadhere);
        } elseif (!$this->delete && file_exists($uploadhere)) {
            $savename = $this->uploadName . "_" . $this->random_str(10) . "." . $this->extension;
            $uploadhere = $this->uploadPath . $savename;
        }


        if (!$this->fullPath) {
            self::$data = $savename;
        } else {
            self::$data = $uploadhere;
        }

        if (!move_uploaded_file($pathname, $uploadhere)) {
            return false;
        }

        return true;
    }

    function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}