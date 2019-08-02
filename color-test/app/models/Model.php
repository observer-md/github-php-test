<?php
require_once (DIR_APP . '/libs/DB.php');

/**
 * Model interface
 */
abstract class Model
{
    /**
     * Return DB connection
     */
    public static function db()
    {
        return DB::init()->getConn();
    }

    /**
     * Init model object
     */
    public static function init()
    {
        return new static();
    }
}