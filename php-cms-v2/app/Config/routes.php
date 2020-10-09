<?php
use Core\Route;


/**
 * API Routes
 * 
 * http://test1.webdev.my/api/articles
 * 
 * @package     Core\Route
 * @author      GEE
 */
Route::get('/api/articles/?', '\App\Controllers\Api\Article@index');
Route::get('/api/articles/(\d+)', '\App\Controllers\Api\Article@get');
Route::post('/api/articles/(\d+)', '\App\Controllers\Api\Article@get');
Route::put('/api/articles/(\d+)', '\App\Controllers\Api\Article@get');
Route::delete('/api/articles/(\d+)', '\App\Controllers\Api\Article@get');

/**
 * Routes
 */
Route::get('/admin/user/settings', '\App\Controllers\Admin@settings');
Route::post('/admin/user/settings', '\App\Controllers\Admin@settings');
Route::get('/admin/user/settings/(\d+)', '\App\Controllers\Admin@settings');
Route::get('/admin/user/settings/(\d+)/email/(en|fr)/show/(\d+)', '\App\Controllers\Admin@settings');

Route::get('/user', '\App\Controllers\User@index');
