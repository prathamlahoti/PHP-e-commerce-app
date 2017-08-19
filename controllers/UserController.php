<?php

namespace App\controllers;

use App\components\Security;
use App\errors\HttpSecurityException;
use App\models\User;

/**
 * Class UserController allows user to be authorized or register on the site.
 */
class UserController
{
    /**
     * action allows user be registered on the site.
     *
     * @return bool true
     */
    public function actionRegister()
    {
        $name = false;
        $email = false;
        $password = false;
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
            $email = $_POST['email'];
            $password = $_POST['password'];
            $errors = false; // Errors checker

            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            if ($errors === false) {
                User::register($name, $email, $password); // unless mistakes aren't found, register a user
                header('Location: /user/login');
            }
        }

        require_once ROOT.'/views/user/register.php';

        return true;
    }

    /**
     * action allows user be authorized on the site.
     *
     * @return bool true
     */
    public function actionLogin()
    {
        $email = false;
        $password = false;

        if (isset($_POST['submit']) && isset($_SESSION['csrf_'])) {
            try{
                if (!Security::hashComparison($_SESSION['csrf_'], $_POST['csrf_token'])) {
                    throw new HttpSecurityException("CSRF attack detected");
                }
            } catch (HttpSecurityException $ex) {
                echo $ex->getMessage();
            }

            $email = $_POST['email'];
            $password = $_POST['password'];
            $errors = false; // Errors checker

            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email!';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-и символов!';
            }

            $userId = User::checkUserData($email, $password);

            if ($userId === false) { // if information is false, then show the error
                $errors[] = 'Неправильные данные для входа на сайт';
            } else { // if information is true, then record the user to session
                User::auth($userId);
                header('Location: /cabinet/');
            }
        }

        require_once ROOT.'/views/user/login.php';

        return true;
    }

    /**
     * action is used to logout user whether he is authorized.
     *
     * @return void
     */
    public function actionLogout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['csrf_']);
        header('Location: /');
    }
}
