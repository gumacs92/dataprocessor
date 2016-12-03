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
    const MODE_DEFAULT = 0;
    const MODE_NEGATED = 1;

    private static $messageTemplates = [
        self::MODE_DEFAULT => [
            "allOf" => "All of the following criteria must met:",
            //TODO extra
            "alnum" => "{{name}} contain not only alfanumeric characters",
            "arrayVal" => "{{name}} is not an array val",
            "between" => "{{name}} must be between {{min}} and {{max}}",
            "boolType" => "{{name}} is not a boolean type",
            "boolVal" => "{{name}} is not a boolean value",
            "date" => "{{name}} is not date format {{format}}",
            "dateFormat" => "Cannot convert {{name}} to {{format}}",
            //TODO extra
            "digit" => "{{name}} must contain only digits",
            "each" => "For each value the following criteria must met:",
            "empty" => "{{name}} must be set",
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
            "not" => "The following are not allowed:",
            //TODO extra
            "noWhitespaces" => "{{name}} cannot contain whitespaces",
            "numeric" => "{{name}} value is not numeric",
            "oneOf" => "Atleast one of the following criteria must met:",
            "optional" => "",
            "phone" => "The given {{name}} is not a phone number",
            "removeHtmlTags" => "Couldn't clear {{name}} from illegal html tags",
            "setTypeBool" => "Couldn't set {{name}}'s type to bool",
            "setTypeFloat" => "Couldn't set {{name}}'s type to float",
            "setTypeInt" => "Couldn't set {{name}}'s type to int",
            "setTypeString" => "Couldn't set {{name}}'s type to string",
            "stringType" => "{{name}} is not a string type",
            //TODO extra
            "unicodeAlnum" => "{{name}} contain not only unicode alfanumeric characters",
        ],
        self::MODE_NEGATED =>[
            "allOf" => "Not all of the following criteria must met:",
            //TODO extra
            "alnum" => "{{name}} contain only alfanumeric characters",
            "arrayVal" => "{{name}} is an array val",
            "between" => "{{name}} must be outside {{min}} and {{max}}",
            "boolType" => "{{name}} is a boolean type",
            "boolVal" => "{{name}} is a boolean value",
            "date" => "{{name}} is a date format {{format}}",
            "dateFormat" => "Converted {{name}} to {{format}}",
            //TODO extra
            "digit" => "{{name}}contains digits",
            "each" => "For each value the following criteria must not met:",
            "empty" => "{{name}} must be empty",
            "escapeHtmlTags" => "Escapeed {{name}}'s html tags",
            "fileMimeType" => "{{name}} is {{mimeType}}",
            "fileMoveUpload" => "Moved {{name}} upload to the save location",
            "fileNoError" => ["not_files_global" => "", "exceeded_default_limit" => "The upload of {{name}} not exceeded the max file size {{size}}", "exceeded_form_limit" => "The upload of {{name}} not exceeded the max file size {{size}}", "partial_upload" => "The upload of {{name}} was not partial", "no_upload" => "The file was uploaded for {{name}}", "missing_tmp" => "Temporary folder found. Ask the administrator for more information.", "failed_to_write" => "{{name}} has been written to the server.", "php_stopped_upload" => "A PHP extension not stopped the upload of {{name}}", "unknown" => "No error occured during {{name}} upload. Ask the administrator for more information."],
            "fileSize" => "{{name}} file must be outside {{minSize}} and {{maxSize}}",
            "fileUploaded" => "{{name}} file has been uploaded",
            "filterValidator" => '{{name}} is valid by {{filter}} filter',
            "floatType" => "{{name}} is a float type",
            "floatVal" => "{{name}} is a float value",
            "in" => "The given {{name}} is in the list",
            "intType" => "{{name}} is an integer type",
            "intVal" => "{{name}} is an integer value",
            "length" => "{{name}}'s length must be outside {{min}} and {{max}}",
            "noneOf" => "One of the following criteria has met:",
            "not" => "The following are allowed:",
            //TODO extra
            "noWhitespaces" => "{{name}} contains whitespaces",
            "numeric" => "{{name}} value is numeric",
            "oneOf" => "Atleast one of the following criteria must met:",
            "optional" => "",
            "phone" => "The given {{name}} is a phone number",
            "removeHtmlTags" => "Cleared {{name}} from illegal html tags",
            "setTypeBool" => "{{name}} has been set to bool type",
            "setTypeFloat" => "{{name}} has been set to float type",
            "setTypeInt" => "{{name}} has been set to int type",
            "setTypeString" => "{{name}} has been set to stringtype",
            "stringType" => "{{name}} is string type",
            //TODO extra
            "unicodeAlnum" => "{{name}} contain unicode alfanumeric characters",
        ]
    ];

    public static function getErrorSetting($ruleName, $mode = RuleSettings::MODE_DEFAULT)
    {
        return self::$messageTemplates[$mode][$ruleName];
    }

    public static function setErrorSetting($ruleName, $errorMessage, $mode = RuleSettings::MODE_DEFAULT)
    {
        self::$messageTemplates[$mode][$ruleName] = $errorMessage;
    }
}