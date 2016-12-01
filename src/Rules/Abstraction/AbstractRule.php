<?php
namespace Processor\Rules\Abstraction;

use Processor\Exceptions\InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;

/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-04
 * Time: 03:15 PM
 */
abstract class AbstractRule implements IValidatable
{
    protected $data;
    protected $feedback;
    protected $name;
    protected $error = "";
    protected $returnErrors = [];
    protected $ruleName;
    protected $template = [
        'name' => '',
    ];

    public function __construct()
    {
        $namespace = explode("\\", get_class($this));
        $classname = $namespace[sizeof($namespace) - 1];
        $this->ruleName = lcfirst(substr($classname, 0, -4));
    }

    abstract public function rule();

    public final function process($data, $feedback = Errors::NONE)
    {
        $this->data = $data;
        $this->feedback = $feedback;
        if (!$this->rule()) {
            if (empty($this->returnErrors)) {
                $this->returnErrors[$this->ruleName] = $this->getMockedErrorMessage();
            }
            return false;
        }
        return true;
    }


    protected final function addDataProcessorErrors($errors)
    {
        if (!key_exists($this->ruleName, $this->returnErrors)) {
            $this->returnErrors[$this->ruleName][$this->ruleName] = $this->getMockedErrorMessage();
        }

        if ($this->feedback === Errors::ONE) {
            $this->returnErrors[$this->ruleName][key($errors)] = current($errors);
        } elseif ($this->feedback === Errors::ALL) {
            $i = 0;
            $key = $this->ruleName . $i;
            while (key_exists($key, $this->returnErrors[$this->ruleName])) {
                $i++;
                $key = $this->ruleName . $i;
            }

            foreach ($errors as $k => $v) {
                $this->returnErrors[$this->ruleName][$key][$k] = $v;
            }

        }
    }

    final private function validateType($value, $type)
    {
        $typeof = "is_" . $type;
        if (function_exists($typeof) && !is_null($value)) {
            if (!$typeof($value)) {
                return false;
            }
        } else {
            if (!($value instanceof $type) && !is_null($value)) {
                return false;
            }
        }
        return true;
    }

    final public function typeCheck($value, $type)
    {
        $success = false;
        if (is_array($type)) {
            foreach ($type as $t) {
                if ($this->validateType($value, $t)) {
                    $success = true;
                    break;
                }
            }
        } else {
            if ($this->validateType($value, $type)) {
                $success = true;
            }
        }
        if (!$success) {
            throw new InvalidArgumentException('Fatal error: ' . $type . ' expected for rule ' . get_class($this) .
                ", got " . gettype($value) . " instead");
        }

        return $value;
    }

    final private function replaceTags($message)
    {
        //TODO a little rework needed to only replace wanted tags;
        $msg = $message;
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $prop) {
            $tag = $prop->getName();
            $prop->setAccessible(true);
            $value = $prop->getValue($this);
//        }
//        foreach ($this->template as $tag => $value) {
            if (!empty($value) && ($value !== 0 && $value !== '' && !is_object($value) && !is_array($value))) {
                $search = "/({{)($tag)(}})/";
                $replace = $value;
                $msg = preg_replace($search, $replace, $msg);
            }
        }

        return $msg;
    }

    final public function getMockedErrorMessage()
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
    final public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     * @throws InvalidArgumentException
     */
    final public function setName($name)
    {
        if (!is_string($name) && !is_null($name)) {
            $this->returnErrors['invalid_type_error'] = 'Fatal error: string expected for setName, got ' . gettype($name) . " instead";
            throw new InvalidArgumentException($this->returnErrors['invalid_type_error']);
        }
        if ($name === null) {
            $this->name = null;
            $this->template['name'] = '';
        } else {
            $this->name = $name;
            $this->template['name'] = $name;
        }
        return $this;

    }


    final public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    final public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    final public function getReturnErrors()
    {
        return $this->returnErrors;
    }
}