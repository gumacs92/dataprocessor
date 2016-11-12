<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 01:28 PM
 */

namespace Processor\Rules;


class RuleSettings
{
    private static $expectedArguments = [
        "alnum" => [["type" => "string", "field" => "extraCharacters", "template" => true, "optional" => ""]],
        "allOf" => [["type" => "Processor\\DataProcessor", "field" => "processors", "varying" => true]],
        "between" => [["type" => "integer", "field" => "min", "template" => true], ["type" => "integer", "field" => "max", "template" => true], ["type" => "bool", "field" => "inclusive", "optional" => true]],
        "digit" => [["type" => "string", "field" => "extraCharacters", "template" => true, "optional" => ""]],
        "each" => [["type" => "Processor\\DataProcessor", "field" => "valueProcessor"]], ["type" => "Processor\\DataProcessor", "field" => "keyProcessor", "optional" => "null"],
        "fileNoError" => [["type" => "integer", "field" => "size", "template" => true, "optional" => ""]],
        "fileMimeType" => [["type" => "string", "field" => "mimeType", "template" => true]],
        "fileMoveUpload" => [["type" => "string", "field" => "uniqueId"], ["type" => "string", "field" => "uploadPath", "template" => true], ["type" => "string", "field" => "uploadName", "optional" => "null"], ["type" => "string", "field" => "extension", "optional" => "null"], ["type" => "bool", "field" => "fullPath", "optional" => false], ["type" => "bool", "field" => "delete", "optional" => true]],
        "fileSize" => [["type" => "integer", "field" => "minSize", "template" => true], ["type" => "integer", "field" => "maxSize", "template" => true, "optional" => "null"]],
        "fileUploaded" => [],
        "floatType" => [],
        "in" => [["type" => "array", "field" => "list"], ["type" => "bool", "field" => "strict", "optional" => false]],
        "intCast" => [],
        "intType" => [],
        "length" => [["type" => "integer", "field" => "min", "template" => true], ["type" => "integer", "field" => "max", "template" => true], ["type" => "bool", "field" => "inclusive", "optional" => true]],
        "noneOf" => [["type" => "Processor\\DataProcessor", "field" => "processors", "varying" => true]],
        "noWhitespaces" => [["type" => "string", "field" => "extraCharacters", "template" => true, "optional" => ""]],
        "numeric" => [],
        "stringType" => [],
        "oneOf" => [["type" => "Processor\\DataProcessor", "field" => "processors", "varying" => true]],
        "optional" => [["type" => "Processor\\DataProcessor", "field" => "processor"]],
    ];

    private static $expectedValue = [
        //TODO standard type
        "alnum" => ["string"],
        "allOf" => ["mixed"],
        "between" => ["integer", "float"],
        "digit" => ["string"],
        "each" => ["array"],
        "fileNoError" => ["array"],
        "fileMimeType" => ["array", "string", "\\SplFileInfo"],
        "fileMoveUpload" => ["array", "string", "\\SplFileInfo"],
        "fileSize" => ["array", "string", "\\SplFileInfo"],
        "fileUploaded" => ["array", "string", "\\SplFileInfo"],
        "floatType" => ["mixed"],
        "in" => ["mixed"],
        "intCast" => ["mixed"],
        "intType" => ["mixed"],
        "length" => ["string", "array"],
        "noneOf" => ["mixed"],
        "noWhitespaces" => ["string"],
        "numeric" => ["mixed"],
        "stringType" => ['mixed'],
        "oneOf" => ["mixed"],
        "optional" => ["mixed"],
    ];

    private static $messages = [
        //TODO extra
        "alnum" => "{{name}} contain not only alfanumeric charachters",
        "allOf" => "All of the following criteria must met:",
        "between" => "{{name}} must be between {{min}} and {{max}}",
        //TODO extra
        "digit" => "{{name}} must contain only digits",
        "each" => "For each value the following criteria must met:",
        "floatType" => "{{name}} is not a float value",
        "fileNoError" => ["not_files_global" => "", "exceeded_default_limit" => "The upload of {{name}} exceeded the max file size {{size}}", "exceeded_form_limit" => "The upload of {{name}} exceeded the max file size {{size}}", "partial_upload" => "The upload of {{name}} was partial", "no_upload" => "No file was uploaded for {{name}}", "missing_tmp" => "Missing temporary folder during upload. Ask the administrator for more information.", "failed_to_write" => "Failed to write {{name}} to the server.", "php_stopped_upload" => "A PHP extension stopped the upload of {{name}}", "unknown" => "Unknown error occured during {{name}} upload. Ask the administrator for more information."],
        "fileMimeType" => "{{name}} is not a {{mimeType}} or not a file",
        "fileMoveUpload" => "Couldn't move {{name}} upload to the save location",
        "fileSize" => "{{name}} file must be between {{minSize}} and {{maxSize}}",
        "fileUploaded" => "{{name}} file has not been uploaded",
        "in" => "The given {{name}} is not in the list",
        "intCast" => "{{name}} couldn't be cast to integer",
        "intType" => "{{name}} is not an integer value",
        "length" => "{{name}}'s length must be between {{min}} and {{max}}",
        "noneOf" => "None of the following criteria must met:",
        //TODO extra
        "noWhitespaces" => "{{name}} cannot contain whitespaces",
        "numeric" => "{{name}} value is not numeric",
        "stringType" => "{{name}} is not a string value",
        "oneOf" => "Atleast one of the following criteria must met:",
        "optional" => "",
    ];

    public static function getArgumentsSetting($ruleName)
    {
        return self::$expectedArguments[$ruleName];
    }

    public static function getValueSetting($ruleName)
    {
        return self::$expectedValue[$ruleName];
    }

    public static function getErrorSetting($ruleName)
    {
        return self::$messages[$ruleName];
    }

    public static function setErrorSetting($ruleName, $errorMessage)
    {
        self::$messages[$ruleName] = $errorMessage;
    }
}