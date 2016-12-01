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

    public function __construct($uniqueId, $uploadPath, $uploadName = null, $extension = null, $fullPath = false, $delete = true)
    {
        parent::__construct();

        $this->uniqueId = $this->typeCheck($uniqueId, 'string');
        $this->uploadPath = $this->typeCheck($uploadPath, 'string');

        $this->uploadName = $this->typeCheck($uploadName, 'string');
        $this->extension = $this->typeCheck($extension, 'string');
        $this->fullPath = $this->typeCheck($fullPath, 'bool');
        $this->delete = $this->typeCheck($delete, 'bool');
    }

    public function rule()
    {
        if (isset($this->data['tmp_name']) && isset($this->data['name'])) {
            $tmpfile = new \SplFileInfo($this->data['tmp_name']);
            $file = new \SplFileInfo($this->data['name']);

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

        } elseif (is_string($this->data) && is_file($this->data)) {
            $tmpfile = new \SplFileInfo($this->data);

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
        } elseif ($this->data instanceof \SplFileInfo) {
            /* @var \SplFileInfo $tmpfile */
            $tmpfile = $this->data;

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
            $this->data = $savename;
        } else {
            $this->data = $uploadhere;
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