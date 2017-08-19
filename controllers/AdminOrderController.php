<?php

namespace App\controllers;

use App\components\Security;
use App\errors\HttpSecurityException;
use App\models\AdminBase;
use App\models\Order;
use App\models\Product;

/**
 * Class AdminOrderController is responsible for handling user product orders.
 */
class AdminOrderController extends AdminBase
{
    /**
     * action for checking page with user orders.
     *
     * @return bool true
     */
    public function actionIndex()
    {
        self::checkAdmin();
        $ordersList = Order::getOrderList();

        require_once ROOT.'/views/admin_order/index.php';

        return true;
    }

    /**
     * action for checking particular order by its id.
     *
     * @param int $id - order id
     *
     * @return bool true
     */
    public function actionView($id)
    {
        self::checkAdmin();
        $order = Order::getOrderById($id);
        $productsQuantity = json_decode($order['products'], true); // Receiving array with ids and count of products
        $productsIds = array_keys($productsQuantity); // Receiving array with ids of products
        $products = Product::getProductsByIds($productsIds); // Receiving list of products in order

        require_once ROOT.'/views/admin_order/view.php';

        return true;
    }

    /**
     * action for updating user orders.
     *
     * @param int $id - order id
     *
     * @return bool true
     */
    public function actionUpdate($id)
    {
        self::checkAdmin();
        $order = Order::getOrderById($id);

        if (isset($_POST['submit']) && isset($_SESSION['csrf_'])) {
            try{
                if (!Security::hashComparison($_SESSION['csrf_'], $_POST['csrf_token'])) {
                    throw new HttpSecurityException("CSRF attack detected");
                }
            } catch (HttpSecurityException $ex) {
                echo $ex->getMessage();
            }
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            $date = $_POST['date'];
            $status = $_POST['status'];
            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status); // Saving changes
            header("Location: /admin/order/view/$id");
        }
        require_once ROOT.'/views/admin_order/update.php';

        return true;
    }

    /**
     * action for deleting particular order by its id.
     *
     * @param int $id - order id
     *
     * @return bool true
     */
    public function actionDelete($id)
    {
        self::checkAdmin();
        if (isset($_POST['submit']) && isset($_SESSION['csrf_'])) {
            try{
                if (!Security::hashComparison($_SESSION['csrf_'], $_POST['csrf_token'])) {
                    throw new HttpSecurityException("CSRF attack detected");
                }
            } catch (HttpSecurityException $ex) {
                echo $ex->getMessage();
            }
            Order::deleteOrderById($id);
            header('Location: /admin/order');
        }
        require_once ROOT.'/views/admin_order/delete.php';

        return true;
    }
}