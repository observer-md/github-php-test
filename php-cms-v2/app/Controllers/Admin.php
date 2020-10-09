<?php
namespace App\Controllers;

/**
 * Admin controller class
 */
class Admin extends \Core\Controller
{
    /**
     * Settings action method
     */
    public function settings($userId, $lang = 'en')
    {
        var_dump(__METHOD__, $userId, $lang);
    }
}
