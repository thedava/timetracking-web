<?php
/**
 * Created by PhpStorm.
 * User: dava
 * Date: 01.11.16
 * Time: 23:40
 */

namespace TimeTracking\View\Helper;


trait EscapeViewHelper
{
    /**
     * Escape the given value for safe display in HTML pages
     *
     * @param string $value
     *
     * @return string
     */
    public function escape($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8');
    }
}
