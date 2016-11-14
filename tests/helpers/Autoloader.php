<?php

namespace Tests\Helpers;

/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 04:34 PM
 */
class AutoLoader
{

    static private $classNames = array();

    /**
     * Store the filename (sans extension) & full path of all ".php" files found
     */
    public static function registerDirectory($dirName)
    {

        $di = new \DirectoryIterator($dirName);
        foreach ($di as $file) {

            if ($file->isDir() && !$file->isLink() && !$file->isDot()) {
                // recurse into directories generic than a few special ones
                self::registerDirectory($file->getPathname());
            } elseif (substr($file->getFilename(), -4) === '.php') {
                // save the class name / path of a .php file found
                $className = substr($file->getFilename(), 0, -4);
                AutoLoader::registerClass($className, $file->getPathname());
            }
        }
    }

    public static function registerClass($className, $fileName)
    {
        AutoLoader::$classNames[$className] = $fileName;
    }

    public static function loadClass($className)
    {
        $dir = explode(DS, $className);
        if (isset(AutoLoader::$classNames[$dir[sizeof($dir) - 1]])) {
            require_once(AutoLoader::$classNames[$dir[sizeof($dir) - 1]]);
        }
    }

}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')) {
    define('ROOT', getcwd() . DS);
}
define('SRC_PATH', ROOT . 'src' . DS);
define('TESTS_PATH', ROOT . 'tests' . DS);
define('VENDOR_PATH', ROOT . 'vendor' . DS);

spl_autoload_register(array(AutoLoader::class, 'loadClass'));
// Register the directory to your include files
AutoLoader::registerDirectory(SRC_PATH);
AutoLoader::registerDirectory(TESTS_PATH);

