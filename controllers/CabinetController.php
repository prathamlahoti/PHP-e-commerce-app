<?php

namespace App\controllers;

use App\components\Security;
use App\errors\HttpSecurityException;
use App\models\User;

/**
 * Class CabinetController is responsible for user information and its manipulations.
 */
class CabinetController
{
    /**
     * action for checking user profile and showing its features.
     *
     * @return bool true
     */
    public function actionIndex()
    {
        $userId = User::checkLogged(); // Receiving user id from session
        $user = User::getUserById($userId); // Receiving information about user

        require_once ROOT.'/views/cabinet/index.php';

        return true;
    }

    /**
     * action for updating user information.
     *
     * @return bool true
     */
    public function actionEdit()
    {
        $userId = User::checkLogged();
        $user = User::getUserById($userId);
        $name = $user['name'];
        $result = false;

        if (isset($_POST['submit']) && isset($_SESSION['csrf_'])) {
            try{
                if (!Security::hashComparison($_SESSION['csrf_'], $_POST['csrf_token'])) {
                    throw new HttpSecurityException("CSRF attack detected");
                }
            } catch (HttpSecurityException $ex) {
                echo $ex->getMessage();
            }
            $name = $_POST['name'];
            $password = $_POST['password'];
            $errors = false;

            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-и символов!';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-и символов!';
            }
            if ($errors === false) {
                $result = User::edit($userId, $name, $password); // Changing user information
            }
        }

        require_once ROOT.'/views/user/edit.php';

        return true;
    }
}
