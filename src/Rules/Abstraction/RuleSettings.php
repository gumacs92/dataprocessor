<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-07
 * Time: 01:28 PM
 */

namespace Processor\Rules\Abstraction;


class RuleSettings
{
    private static $expectedArguments = [
        "allOf" => [["type" => "Processor\\DataProcessor", "field" => "processors", "varying" => true]],
        "alnum" => [["type" => "string", "field" => "extraCharacters", "template" => true, "optional" => ""]],
        "arrayVal" => [],
        //TODO accepting more types
        "between" => [["type" => "integer", "field" => "min", "template" => true], ["type" => "integer", "field" => "max", "template" => true], ["type" => "bool", "field" => "inclusive", "optional" => true]],
        "boolType" => [],
        "boolVal" => [],
        "date" => [["type" => "string", "field" => "format", "template" => true, "optional" => ""], ["type" => "bool", "field" => "convert", "optional" => false]],
        "digit" => [["type" => "string", "field" => "extraCharacters", "template" => true, "optional" => ""]],
        "each" => [["type" => "Processor\\DataProcessor", "field" => "valueProcessor"]], ["type" => "Processor\\DataProcessor", "field" => "keyProcessor", "optional" => "null"],
        "escapeHtmlTags" => [],
        "fileMimeType" => [["type" => "string", "field" => "mimeType", "template" => true]],
        "fileMoveUpload" => [["type" => "string", "field" => "uniqueId"], ["type" => "string", "field" => "uploadPath", "template" => true], ["type" => "string", "field" => "uploadName", "optional" => "null"], ["type" => "string", "field" => "extension", "optional" => "null"], ["type" => "bool", "field" => "fullPath", "optional" => false], ["type" => "bool", "field" => "delete", "optional" => true]],
        "fileNoError" => [["type" => "integer", "field" => "size", "template" => true, "optional" => ""]],
        "fileSize" => [["type" => "integer", "field" => "minSize", "template" => true], ["type" => "integer", "field" => "maxSize", "template" => true, "optional" => "null"]],
        "fileUploaded" => [],
        "filterValidator" => [["type" => "integer", "field" => "filter", "template" => true], ["type" => "integer", "field" => "options", "optional" => "null"]],
        "floatType" => [],
        "floatVal" => [],
        "in" => [["type" => "array", "field" => "list"], ["type" => "bool", "field" => "strict", "optional" => false]],
        "intType" => [],
        "intVal" => [],
        "length" => [["type" => "integer", "field" => "min", "template" => true], ["type" => "integer", "field" => "max", "template" => true], ["type" => "bool", "field" => "inclusive", "optional" => true]],
        "noneOf" => [["type" => "Processor\\DataProcessor", "field" => "processors", "varying" => true]],
        "noWhitespaces" => [["type" => "string", "field" => "extraCharacters", "template" => true, "optional" => ""]],
        "numeric" => [],
        "oneOf" => [["type" => "Processor\\DataProcessor", "field" => "processors", "varying" => true]],
        "optional" => [["type" => "Processor\\DataProcessor", "field" => "processor"]],
        "phone" => [],
        "removeHtmlTags" => [["type" => "\\HTMLPurifier_Config", "field" => "config", "optional" => "null"]],
        "setTypeBool" => [],
        "setTypeFloat" => [],
        "setTypeInt" => [],
        "setTypeString" => [],
        "stringType" => [],
        "unicodeAlnum" => [["type" => "string", "field" => "extraCharacters", "template" => true, "optional" => ""]],
    ];

    private static $expectedValue = [
        //TODO standard type
        "allOf" => ["mixed"],
        "alnum" => ["string"],
        "arrayVal" => ["mixed"],
        "between" => ["integer", "float"],
        "boolType" => ['mixed'],
        "boolVal" => ['mixed'],
        "date" => ['mixed'],
        "digit" => ["string"],
        "each" => ["array"],
        "escapeHtmlTags" => ["string"],
        "fileMimeType" => ["array", "string", "\\SplFileInfo"],
        "fileMoveUpload" => ["array", "string", "\\SplFileInfo"],
        "fileNoError" => ["array"],
        "fileSize" => ["array", "string", "\\SplFileInfo"],
        "fileUploaded" => ["array", "string", "\\SplFileInfo"],
        "filterValidator" => ['mixed'],
        "floatType" => ["mixed"],
        "floatVal" => ['mixed'],
        "in" => ["mixed"],
        "intType" => ["mixed"],
        "intVal" => ['mixed'],
        "length" => ["string", "array"],
        "noneOf" => ["mixed"],
        "noWhitespaces" => ["string"],
        "numeric" => ["mixed"],
        "oneOf" => ["mixed"],
        "optional" => ["mixed"],
        "phone" => ["string"],
        "removeHtmlTags" => ["string"],
        "setTypeBool" => ['integer', 'bool', 'float', 'string'],
        "setTypeFloat" => ['integer', 'bool', 'float', 'string'],
        "setTypeInt" => ['integer', 'bool', 'float', 'string'],
        "setTypeString" => ['integer', 'bool', 'float', 'string'],
        "stringType" => ['mixed'],
        "unicodeAlnum" => ["string"],
    ];

    private static $messages = [
        "allOf" => "All of the following criteria must met:",
        //TODO extra
        "alnum" => "{{name}} contain not only alfanumeric characters",
        "arrayVal" => "{{name}} is not an array val",
        "between" => "{{name}} must be between {{min}} and {{max}}",
        "boolType" => "{{name}} is not a boolean type",
        "boolVal" => "{{name}} is not a boolean value",
        "date" => "{{name}} is not date format {{format}}",
        //TODO extra
        "digit" => "{{name}} must contain only digits",
        "each" => "For each value the following criteria must met:",
        "escapeHtmlTags" => "Couldn't escape {{name}}'s html tags",
        "fileMimeType" => "{{name}} is not a {{mimeType}} or not a file",
        "fileMoveUpload" => "Couldn't move {{name}} upload to the save location",
        "fileNoError" => ["not_files_global" => "", "exceeded_default_limit" => "The upload of {{name}} exceeded the max file size {{size}}", "exceeded_form_limit" => "The upload of {{name}} exceeded the max file size {{size}}", "partial_upload" => "The upload of {{name}} was partial", "no_upload" => "No file was uploaded for {{name}}", "missing_tmp" => "Missing temporary folder during upload. Ask the administrator for more information.", "failed_to_write" => "Failed to write {{name}} to the server.", "php_stopped_upload" => "A PHP extension stopped the upload of {{name}}", "unknown" => "Unknown error occured during {{name}} upload. Ask the administrator for more information."],
        "fileSize" => "{{name}} file must be between {{minSize}} and {{maxSize}}",
        "fileUploaded" => "{{name}} file has not been uploaded",
        "filterValidator" => '{{name}} is not valid by {{filter}} filter',
        "floatType" => "{{name}} is not a float type",
        "floatVal" => "{{name}} is not a float value",
        "in" => "The given {{name}} is not in the list",
        "intType" => "{{name}} is not an integer type",
        "intVal" => "{{name}} is not an integer value",
        "length" => "{{name}}'s length must be between {{min}} and {{max}}",
        "noneOf" => "None of the following criteria must met:",
        //TODO extra
        "noWhitespaces" => "{{name}} cannot contain whitespaces",
        "numeric" => "{{name}} value is not numeric",
        "oneOf" => "Atleast one of the following criteria must met:",
        "optional" => "",
        "phone" => "The given {{name}} is not a phone number",
        "removeHtmlTags" => "Couldn't clear {{name}} form illegal html tags",
        "setTypeBool" => "Couldn't set {{name}}'s type to bool",
        "setTypeFloat" => "Couldn't set {{name}}'s type to float",
        "setTypeInt" => "Couldn't set {{name}}'s type to int",
        "setTypeString" => "Couldn't set {{name}}'s type to string",
        "stringType" => "{{name}} is not a string type",
        //TODO extra
        "unicodeAlnum" => "{{name}} contain not only unicode alfanumeric characters",
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