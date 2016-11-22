<?php
/**
 * Created by PhpStorm.
 * User: Gumacs
 * Date: 2016-11-14
 * Time: 02:36 PM
 */

namespace Processor\Rules;

use Processor\Rules\Abstraction\AbstractRule;

class EscapeHtmlTagsRule extends AbstractRule
{
    public function rule()
    {
        parent::rule();

        self::$data = htmlspecialchars(self::$data);

        return true;
    }

}