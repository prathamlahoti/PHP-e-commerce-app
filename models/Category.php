<?php

namespace App\models;

use App\components\Db;
use PDO;

/**
 * Class Category is responsible for validating and manipulations with category data.
 */
class Category
{
    /**
     * @const PRODUCT_STATUS - flag of true result(status)
     */
    const PRODUCT_STATUS = 1;

    /**
     * method for retrieving all available categories.
     *
     * @param int $status - category availability
     *
     * @return array - the list of categories
     */
    public static function getCategoriesList($status = self::PRODUCT_STATUS)
    {
        $db = Db::getConnection();
        $sql = 'SELECT id, name, status FROM category WHERE status=:status';
        $result = $db->prepare($sql);
        $result->execute([':status' => $status]);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for retrieving all categories for admin features.
     *
     * @return array - the list of categories
     */
    public static function getCategoriesListAdmin()
    {
        $db = DB::getConnection();
        $sql = 'SELECT * FROM category';
        $result = $db->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for receiving status message depending on $status value.
     *
     * @param bool $status - true or false result
     *
     * @return string - status value
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case 0: return 'Не отображается';
            case 1: return 'Отображается';
        }
    }

    /**
     * method for retrieving particular category by its id.
     *
     * @param int $id - category id
     *
     * @return array - category information
     */
    public static function getCategoryById($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM category WHERE id = :id';
        $result = $db->prepare($sql);
        $result->execute([':id' => $id]);

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $name      - category name
     * @param int    $sortOrder - category sort order
     * @param bool   $status    - category availability
     *
     * @return bool - the result of creating a category
     */
    public static function createCategory($name, $sortOrder, $status)
    {
        $db = Db::getConnection();
        $sql = 'INSERT INTO category (name, sort_order, status) VALUES (:name, :sort_order, :status)';

        return $db->prepare($sql)->execute([
            ':name'       => $name,
            ':sort_order' => $sortOrder,
            ':status'     => $status,
        ]);
    }

    /**
     * @param int $id - category id
     *
     * @return bool - the result of deleting a category
     */
    public static function deleteCategoryById($id)
    {
        $db = Db::getConnection();
        $sql = 'DELETE FROM category WHERE id=:id';

        return $db->prepare($sql)->execute([':id' => $id]);
    }

    /**
     * @param int    $id        - category id
     * @param string $name      - category name
     * @param int    $sortOrder - category sort order
     * @param bool   $status    - category availability
     *
     * @return bool - the result of updating a category
     */
    public static function updateCategoryById($id, $name, $sortOrder, $status)
    {
        $db = Db::getConnection();
        $sql = 'UPDATE category SET name=:name, sort_order=:sort_order, status=:status WHERE id=:id';

        return $db->prepare($sql)->execute([
            ':id'         => $id,
            ':name'       => $name,
            ':sort_order' => $sortOrder,
            ':status'     => $status,
        ]);
    }
}
