<?php
namespace app\controllers\api;


/**
 * BaseController class
 */
abstract class BaseController extends \app\controllers\BaseController
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();

        // check authentication 
    }
}