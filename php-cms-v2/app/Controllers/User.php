<?php
namespace App\Controllers;

/**
 * 
 */
class User extends Controller
{
    public function index()
    {
        var_dump(__METHOD__);
        require __DIR__ . '/../Views/user/index.php';
    }
}
