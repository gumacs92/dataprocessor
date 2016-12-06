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
    protected $canBeEmpty = false;

    protected $error = "";

    private $resultErrors = [];
    private $ruleName;
    protected $name;

    public function __construct()
    {
        $namespace = explode("\\", get_class($this));
        $classname = $namespace[sizeof($namespace) - 1];
        $this->ruleName = lcfirst(substr($classname, 0, -4));
    }

    abstract public function rule();

//    public final function callDatProcessor

    public function process($data, $feedback = Errors::NONE)
    {
        $this->feedback = $feedback;

        if (!$this->setData($data)) {
            $this->addEmptyError();
            return false;
        }

        if (!$this->rule()) {
            if (empty($this->resultErrors)) {
                $this->addResultErrorSameLevel();
            }
            return false;
        }
        return true;
    }

    protected final function addEmptyError()
    {
        $this->resultErrors['empty'] = $this->replaceTags(RuleSettings::getErrorSetting('empty'));
    }

    protected final function addResultErrorSameLevel($errors = '', $mode = RuleSettings::MODE_DEFAULT)
    {
        if ($this->feedback !== Errors::NONE) {
            if (!empty($errors)) {
                if (is_array($errors)) {
                    if ($this->feedback === Errors::ONE) {
                        $this->resultErrors[key($errors)] = current($errors);
                    } elseif ($this->feedback === Errors::ALL) {
                        foreach ($errors as $key => $value) {
                            $this->resultErrors[$key] = $value;
                        }
                    }
                } else {
                    $this->resultErrors[$this->ruleName] = $errors;
                }
            } else {
                $this->resultErrors[$this->ruleName] = $this->getMockedErrorMessage($mode);
            }
        }
    }

    protected final function addResultErrorNewLevel($errors = '', $escortMessage = '', $mode = RuleSettings::MODE_DEFAULT)
    {
        if ($this->feedback !== Errors::NONE) {
            if (empty($escortMessage) && !empty($errors)) {
                $this->resultErrors[$this->ruleName][$this->ruleName] = $this->getMockedErrorMessage($mode);
            } else {
                $this->resultErrors[$this->ruleName][$this->ruleName] = $escortMessage;
            }

            if (!empty($errors)) {
                if (is_array($errors)) {
                    if ($this->feedback === Errors::ONE) {
                        $this->resultErrors[$this->ruleName][key($errors)] = current($errors);
                    } elseif ($this->feedback === Errors::ALL) {
                        $i = 0;
                        $key = $this->ruleName . $i;
                        while (key_exists($key, $this->resultErrors[$this->ruleName])) {
                            $i++;
                            $key = $this->ruleName . $i;
                        }

                        foreach ($errors as $k => $v) {
                            $this->resultErrors[$this->ruleName][$key][$k] = $v;
                        }

                    }
                } else {
                    $this->resultErrors[$this->ruleName][$this->ruleName] = $errors;
                }
            } else {
                $this->resultErrors[$this->ruleName][$this->ruleName] = $this->getMockedErrorMessage($mode);
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

    final public function getMockedErrorMessage($mode = RuleSettings::MODE_DEFAULT)
    {
        $error = RuleSettings::getErrorSetting($this->ruleName, $mode);
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
        $this->typeCheck($name, 'string');

        if ($name === null) {
            $this->name = null;
        } else {
            $this->name = $name;
        }
        return $this;

    }


    final private function setData($data)
    {
        if (!$this->canBeEmpty) {
            if ((empty($data) || !isset($data)) && !is_bool($data) && $data == 0) {
                return false;
            }
        }
        $this->data = $data;
        return true;
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
    final public function getResultErrors()
    {
        return $this->resultErrors;
    }

    /**
     * @return string
     */
    final public function getRuleName()
    {
        return $this->ruleName;
    }
}