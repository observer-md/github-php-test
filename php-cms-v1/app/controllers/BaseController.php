<?php
namespace app\controllers;


/**
 * Base Controller class
 */
abstract class BaseController extends \vendor\core\Controller
{
    /**
     * 
     */
    public function __construct($params = null)
    {
        parent::__construct($params);


        $this->title = 'Test';

        /**
         * META-TAG
         */
        // $this->addTagMeta('description', 'Free Web tutorials');
        // $this->addTagMeta('viewport', 'width=device-width, initial-scale=1.0');
        
        /*
         * CSS
         */
        // $this->addTagLink('/assets/css/main.css');

        /*
         * JS
         * https://developers.google.com/speed/libraries/
         * https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js
         */
        // $this->addTagScript('/assets/js/jquery-3.4.1.js');
        // $this->addTagScript('/assets/js/main.js');
        
    }
}