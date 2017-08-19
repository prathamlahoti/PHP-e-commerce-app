<?php

namespace App\models;

use App\components\Db;
use PDO;

/**
 * Class User is responsible for validating and manipulations with user data.
 */
class User
{
    /**
     * @const USER_ROLE - common user or administrator
     */
    const USER_ROLE = 0;
    /**
     * @const NAME_LEN - min value of user name
     */
    const NAME_LEN = 2;
    /**
     * @const PASS_LEN - min value of user password
     */
    const PASS_LEN = 6;

    /**
     * @param string $name     - user name
     * @param string $email    - user email
     * @param string $password - user password
     *
     * @return bool - result of registration
     */
    public static function register($name, $email, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $db = Db::getConnection();
        $sql = 'INSERT INTO user (name, email, password) VALUES(:name, :email, :password)';

        return $db->prepare($sql)->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $password,
        ]);
    }

    /**
     * method for validating user name.
     *
     * @param string $name - user name
     *
     * @return bool - result of validating
     */
    public static function checkName($name)
    {
        return (strlen($name) >= self::NAME_LEN) ? true : false;
    }

    /**
     * method for validating  user email.
     *
     * @param string $email -  user email
     *
     * @return bool - result of validating
     */
    public static function checkEmail($email)
    {
        return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    /**
     * method for validating user password.
     *
     * @param string $password - user password
     *
     * @return bool - result of validating
     */
    public static function checkPassword($password)
    {
        return (strlen($password) >= self::PASS_LEN) ? true : false;
    }

    /**
     * method for searching the existence of user mail.
     *
     * @param string $email - user email
     *
     * @return bool - result of searching
     */
    public static function checkEmailExists($email)
    {
        $db = Db::getConnection();
        $sql = 'SELECT COUNT(*)FROM user WHERE email=:email';
        $result = $db->prepare($sql);
        $result->execute([':email' => $email]);

        return ($result->fetchColumn()) ? true : false;
    }

    /**
     * method for checking comparison of user phone and regex template.
     *
     * @param string $phone   -  user phone number
     * @param string $pattern -  special pattern for mobile number
     *
     * @return bool - result of comparison
     */
    public static function checkPhone($phone, $pattern = '/[0-9]{3}[0-9]{2}[0-9]{2}[0-9]{3}/')
    {
        return (preg_match($pattern, $phone)) ? true : false;
    }

    /**
     * method for checking user access.
     *
     * @param string $email    - user email
     * @param string $password - user password
     *
     * @return int or bool - user id or false
     */
    public static function checkUserData($email, $password)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM user WHERE email=:email';
        $result = $db->prepare($sql);
        $result->execute([':email' => $email]);
        $user = $result->fetch(PDO::FETCH_ASSOC);

        return (password_verify($password, $user['password'])) ? $user['id'] : false; // Checking password hashes
    }

    /**
     * method for authorize user in system.
     *
     * @param int $userId - user id
     *
     * @return void
     */
    public static function auth($userId)
    {
        $_SESSION['user'] = $userId;
    }

    /**
     * method for checking user access in system.
     *
     * @return string or void - result of checking user access
     */
    public static function checkLogged()
    {
        if (isset($_SESSION['user'])) { // whether user session exists, returning user id
            return $_SESSION['user'];
        }
        header('Location: /www/user/login');
    }

    /**
     * method for checking user status.
     *
     * @return bool - true, whether user is guest
     */
    public static function isGuest()
    {
        return (!isset($_SESSION['user'])) ? true : false;
    }

    /**
     * method for retrieving user data by his id.
     *
     * @param int $id - user id
     *
     * @return array - user data
     */
    public static function getUserById($id)
    {
        if ((int) $id) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM user WHERE id=:id';
            $result = $db->prepare($sql);
            $result->execute([':id' => $id]);

            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * method for updating user data.
     *
     * @param int    $id       - user id
     * @param string $name     - user name
     * @param string $password - user password
     *
     * @return bool - result of saving new user data
     */
    public static function edit($id, $name, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $db = Db::getConnection();
        $sql = 'UPDATE user SET name=:name, password=:password WHERE id=:id';

        return $db->prepare($sql)->execute([':id' => $id, ':name' => $name, ':password' => $password]);
    }
}
