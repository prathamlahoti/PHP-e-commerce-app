<?php

namespace App\models;

/**
 * Class AdminBase is responsible for admin access to his features.
 */
abstract class AdminBase
{
    /**
     * method for checking user admin access.
     *
     * @return mixed: boolean or null whether admin access
     */
    public static function checkAdmin()
    {
        $userId = User::checkLogged(); // checking unless the user is authorized
        $user = User::getUserById($userId); // Receiving user info
        return ($user['role'] == 1) ? true : false;
    }
}
