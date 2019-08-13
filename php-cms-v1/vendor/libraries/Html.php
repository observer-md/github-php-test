<?php
namespace vendor\libraries;

/**
 * Html library class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
class Html
{
    /**
     * Return html input text
     */
    public static function input($name, $value = '', $class = '')
    {
        return sprintf('<input class="%s" type="text" value="%s" />', $class, $value);
    }
}