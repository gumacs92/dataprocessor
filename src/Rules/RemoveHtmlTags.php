<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 02:10 PM
 */


namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class RemoveHtmlTags extends AbstractRule
{
    protected $config;

    public function rule()
    {
        parent::rule();

        if (isset($config)) {
            $this->config = $config;
        } elseif ($this->config instanceof \HTMLPurifier_Config) {
            $this->config = \HTMLPurifier_Config::createDefault();
        } else {
            return false;
        }

        $purifier = new \HTMLPurifier($this->config);
        self::$data = $purifier->purify(self::$data);

        return true;
    }
}