<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-08
 * Time: 11:13 AM
 */

namespace Tests\Helpers;


class Tools
{
    public static function searchAndReplace($message, $tags){
        $msg = $message;
        foreach ($tags as $tag => $value) {
            if (!empty($value)) {
                $search = "/({{)($tag)(}})/";
                $replace = $value;
                $msg = preg_replace($search, $replace, $msg);
            }
        }
        return $msg;
    }
}