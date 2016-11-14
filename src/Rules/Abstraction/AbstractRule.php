<?php
namespace Processor\Rules\Abstraction;

use Processor\Exceptions\InvalidArgumentException;
use Processor\Exceptions\RuleException;

/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 03:15 PM
 */
abstract class AbstractRule implements IValidatable
{
    protected static $data;
    protected $nameForErrors;
    protected $error = "";
    protected $returnErrors = [];
    protected $ruleName;
    protected $template = [
        'name' => '',
    ];

    public function rule()
    {
        $this->checkValue();

        return true;
    }

    public function verify($value, $errors = false)
    {
        self::$data = $value;
        if (!$errors) {
            return $this->process();
        } else {
            return $this->processWithErrors();
        }
    }

    public function process()
    {
        return $this->rule();
    }

    public function processWithErrors()
    {
        if (!$this->rule()) {
            $this->returnErrors[$this->ruleName] = $this->getActualErrorMessage();
            throw new RuleException($this->returnErrors[$this->ruleName]);
        }

        return true;
    }

    final private function typeCheck($value, $type)
    {
        $typeof = "is_" . $type;
        if (function_exists($typeof)) {
            if (!$typeof($value)) {
                $this->returnErrors['invalid_argument_type_error'] = 'Fatal error: ' . $type . ' expected for rule ' . get_class($this) .
                    ", got " . gettype($value) . " instead";
                throw new InvalidArgumentException($this->returnErrors['invalid_argument_type_error']);
            }
        } else {
            if (!($value instanceof $type)) {
                $this->returnErrors['invalid_argument_type_error'] = 'Fatal error: ' . $type . ' expected for rule ' . get_class($this) .
                    ", got " . gettype($value) . " instead";
                throw new InvalidArgumentException($this->returnErrors['invalid_argument_type_error']);
            }
        }
    }

    final public function checkArguments($arguments)
    {
        $argsettings = RuleSettings::getArgumentsSetting($this->ruleName);

        $size_normal = 0;
        $size_optional = 0;
        $varying_size = false;

        foreach ($argsettings as $setting) {
            if (isset($setting["optional"])) {
                $size_optional++;
            } elseif (isset($setting['varying'])) {
                $varying_size = true;
                break;
            } else {
                $size_normal++;
            }
        }

        $size_sum = $size_normal + $size_optional;
        if (($size_normal > sizeof($arguments)) || (!$varying_size && ($size_normal !== sizeof($arguments) && $size_sum !== sizeof($arguments)))) {
            $this->returnErrors['argument_size_error'] = 'Fatal error: expected ' . $size_normal . ' or ' . $size_sum . ' arguments for rule ' . get_class($this) .
                ' but got ' . sizeof($arguments);
            throw new InvalidArgumentException($this->returnErrors['argument_size_error']);
        }

        $i = 0;
        while ($i < $size_sum) {
            if (!isset($arguments[$i]) && isset($argsettings[$i]['optional'])) {
                $this->$argsettings[$i]['field'] = $argsettings[$i]['optional'] === "null" ? null : $argsettings[$i]['optional'];
                if (isset($argsettings[$i]['template'])) {
                    $this->template[$argsettings[$i]['field']] = $this->$argsettings[$i]['field'];
                }
            } elseif (isset($arguments[$i]) && !isset($argsettings[$i]['varying'])) {
                $this->typeCheck($arguments[$i], $argsettings[$i]['type']);
                $this->$argsettings[$i]['field'] = $arguments[$i];
                if (isset($argsettings[$i]['template'])) {
                    $this->template[$argsettings[$i]['field']] = $arguments[$i];
                }
            } else {
                break;
            }
            $i++;
        }
        if ($varying_size) {
            for ($j = $i; $j < sizeof($arguments); $j++) {
                $this->typeCheck($arguments[$j], $argsettings[$i]['type']);
                array_push($this->$argsettings[$i]['field'], $arguments[$j]);
            }
        }
    }

    final private function checkValue()
    {
        $valsetting = RuleSettings::getValueSetting($this->ruleName);

        $errors = [];
        $success = false;
        if ($valsetting[0] === "mixed") {
            return self::$data;
        } else {
            //TODO standard type
            foreach ($valsetting as $type) {
                $typeof = "is_" . $type;
                if (function_exists($typeof)) {
                    if (!$typeof(self::$data)) {
                        $errors[] = $type;
                    } else {
                        $success = true;
                        break;
                    }
                } elseif (!self::$data instanceof $type) {
                    $errors[] = $type;
                } else {
                    $success = true;
                    break;
                }
            }
        }
        if (!$success) {
            $this->returnErrors['invalid_value_type_error'] = 'Fatal error: expected ' . implode(" or ", $errors) . ' type for the processed value but got ' . gettype(self::$data) .
                ' in the rule ' . get_class($this);
            throw new InvalidArgumentException($this->returnErrors['invalid_value_type_error']);
        }
    }

    final private function replaceTags($message)
    {
        $msg = $message;
        foreach ($this->template as $tag => $value) {
            if (!empty($value)) {
                $search = "/({{)($tag)(}})/";
                $replace = $value;
                $msg = preg_replace($search, $replace, $msg);
            }
        }

        return $msg;
    }

    final public function getActualErrorMessage()
    {
        $error = RuleSettings::getErrorSetting($this->ruleName);
        if (is_array($error)) {
            $errormsg = $error[$this->error];
        } else {
            $errormsg = $error;
        }
        return $this->replaceTags($errormsg);
    }

    /**
     * @return mixed
     */
    final public function getRuleName()
    {
        return $this->ruleName;
    }

    /**
     * @param mixed $ruleName
     */
    final public function setRuleName($ruleName)
    {
        $this->ruleName = $ruleName;
    }

    /**
     * @return mixed
     */
    final public function getNameForErrors()
    {
        return $this->nameForErrors;
    }

    /**
     * @param mixed $name
     * @return $this
     * @throws InvalidArgumentException
     */
    final public function setNameForErrors($name)
    {
        if (!is_string($name) && !is_null($name)) {
            $this->returnErrors['invalid_type_error'] = 'Fatal error: string expected for setName, got ' . gettype($name) . " instead";
            throw new InvalidArgumentException($this->returnErrors['invalid_type_error']);
        }
        if ($name === null) {
            $this->nameForErrors = null;
            $this->template['name'] = '';
        } else {
            $this->nameForErrors = $name;
            $this->template['name'] = $name;
        }
        return $this;

    }


    final public static function setData($data)
    {
        self::$data = $data;
    }

    /**
     * @return mixed
     */
    final public static function getData()
    {
        return self::$data;
    }

    /**
     * @return array
     */
    final public function getReturnErrors()
    {
        return $this->returnErrors;
    }
}