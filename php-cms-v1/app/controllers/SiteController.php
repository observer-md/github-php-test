<?php
namespace app\controllers;

use vendor\libraries\Hash;
use vendor\libraries\Jwt;
use vendor\libraries\Http;

use app\models\User;

/**
 * SiteController class
 */
class SiteController extends BaseController
{
    /**
     * View layout
     * @var string
     */
    // protected $viewLayout = 'admin/layout';

    /**
     * View directory
     * @var string
     */
    // protected $viewDir    = 'admin/';

    
    /**
     * Default page
     * 
     * @link http://test.mydev.com/
     * @link http://test.mydev.com/?c=site&a=index
     */
    public function actionIndex()
    {
        $url = 'http://test.mydev.com/?c=api/user&a=index&id=2';
        $header = [
            'Authorization: Bearer ' . $this->app->getConfig('api')['clientSecret'],
        ];
        $res = Http::request($url, [], $header);
        
        echo $this->view("site/index", [
            'user' => ($res['user'] ?? null),
        ]);
    }

}