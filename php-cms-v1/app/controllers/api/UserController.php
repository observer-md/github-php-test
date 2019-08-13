<?php
namespace app\controllers\api;

use app\models\User;

/**
 * Api User Controller
 */
class UserController extends BaseController
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default action
     */
    public function actionIndex()
    {
        $id = (int) $this->app->getRequest('get')->get('id');
        
        return $this->json([
            'success' => true,
            'user' => User::init()->find(['id' => $id]),
        ]);
    }
}