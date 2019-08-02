<?php

require_once ("BaseController.php");
require_once (DIR_APP . '/models/ColorModel.php');

/**
 * Home controller
 */
class HomeController extends BaseController
{
    /**
     * Default action
     * @link http://test.com/
     * @link http://test.com/?c=home&a=index
     */
    public function actionIndex()
    {
        $this->view('parts/home', [
            'colors' => ColorModel::init()->getColors(),
        ]);
    }
}
