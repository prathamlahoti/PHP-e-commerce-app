<?php
namespace App\components;
use App\errors\ResourceNotFoundException;

/**
 * Class Db is used to interact with database.
 */
class Db
{
    /**
     * Utilizing a connection with database.
     * @throws - if there isn't database connection
     * @return object|null PDO object
     */
    public static function getConnection()
    {
        try {
            $params = require ROOT.'/config/db_params.php';
            $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
            $opt = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            ];
            return new \PDO($dsn, $params['user'], $params['password'], $opt);
        } catch (\PDOException $ex) {
            echo "Сервер времено недоступен";
            throw new \PDOException("Ошибка подключения к базе данных: ".$ex->getMessage());
        }
    }
}