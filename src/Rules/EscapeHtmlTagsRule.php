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
        $this->data = htmlspecialchars($this->data);

        return true;
    }

}