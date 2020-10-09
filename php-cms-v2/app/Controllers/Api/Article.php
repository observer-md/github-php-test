<?php
namespace App\Controllers\Api;


/**
 * 
 */
class Article extends Controller
{
    /**
     * http://test1.mydev.com/api/articles/
     */
    public function index()
    {
        $cities = \App\Models\City::init()->find();
        $this->json(['data' => $cities->attributes()]);
    }
}
