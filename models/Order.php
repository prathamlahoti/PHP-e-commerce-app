<?php

namespace App\models;

use App\components\Db;
use PDO;

/**
 * Class Order is responsible for realization and manipulations with user orders.
 */
class Order
{
    /**
     * @param string $userName    - user name
     * @param string $userPhone   - user phone
     * @param string $userComment - user comment to order
     * @param int    $userId      - user id
     * @param array  $products    - chosen products by user
     *
     * @return bool - the result of saving an order
     */
    public static function save($userName, $userPhone, $userComment, $userId, $products)
    {
        $db = Db::getConnection();
        $products = json_encode($products); // Converting array to string in json format
        $sql = 'INSERT INTO product_order (user_name, user_phone, user_comment, user_id, products);
                VALUES (:user_name, :user_phone, :user_comment, :user_id, :products)';

        return $db->prepare($sql)->execute([
             ':user_name'    => $userName,
             ':user_phone'   => $userPhone,
             ':user_comment' => $userComment,
             ':user_id'      => $userId,
             ':products'     => $products,
         ]);
    }

    /**
     * method for receiving an order by its id.
     *
     * @param int $id - order id
     *
     * @return array - information about order
     */
    public static function getOrderById($id)
    {
        $db = DB::getConnection();
        $sql = 'SELECT * FROM product_order WHERE id=:id';
        $result = $db->prepare($sql);
        $result->execute([':id' => $id]);

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * method for choosing order status value.
     *
     * @param string $status - value of order status
     *
     * @return string - chosen status
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case 1: return 'Новый заказ';
            case 2: return 'В обработке';
            case 3: return 'Доставляется';
            case 4: return 'Закрыт';
        }
    }

    /**
     * method for retrieving the list of all orders.
     *
     * @return array - the list of all orders
     */
    public static function getOrderList()
    {
        $db = DB::getConnection();
        $sql = 'SELECT id, user_name, user_phone, date, status FROM product_order ORDER BY id ASC';

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for updating order info by its id.
     *
     * @param int    $id          - order id
     * @param string $userName    - user name
     * @param string $userPhone   - user phone
     * @param string $userComment - user comment to order
     * @param string $date        - order date
     * @param bool   $status      - order status
     *
     * @return bool - result of updating an order
     */
    public static function updateOrderById($id, $userName, $userPhone, $userComment, $date, $status)
    {
        $db = Db::getConnection();
        $sql = 'UPDATE product_order SET user_name=:user_name, user_phone=:user_phone, user_comment=:user_comment, date=:date, status=:status WHERE id=:id';

        return $db->prepare($sql)->execute([
            ':id'           => $id,
            ':user_name'    => $userName,
            ':user_phone'   => $userPhone,
            ':user_comment' => $userComment,
            ':date'         => $date,
            ':status'       => $status,
        ]);
    }

    /**
     * method for deleting order by its id.
     *
     * @param int $id - order id
     *
     * @return bool - the result of deleting an order
     */
    public static function deleteOrderById($id)
    {
        $db = Db::getConnection();
        $sql = 'DELETE FROM product_order WHERE id=:id';

        return $db->prepare($sql)->execute([':id' => $id]);
    }
}
