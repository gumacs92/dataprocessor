<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 02:10 PM
 */


namespace Processor\Rules;

use HTMLPurifier_Config;
use Processor\Rules\Abstraction\AbstractRule;

class RemoveHtmlTagsRule extends AbstractRule
{
    protected $config;

    public function __construct($config = null)
    {
        parent::__construct();
        $this->config = $this->typeCheck($config, HTMLPurifier_Config::class);
    }

    public function rule()
    {
        if (!isset($this->config)) {
            $this->config = HTMLPurifier_Config::createDefault();
        }

        $purifier = new \HTMLPurifier($this->config);
        $this->data = $purifier->purify($this->data);

        return true;
    }
}