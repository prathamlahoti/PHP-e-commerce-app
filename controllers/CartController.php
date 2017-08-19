<?php

namespace App\controllers;

use App\components\Security;
use App\errors\HttpSecurityException;
use App\models\Cart;
use App\models\Category;
use App\models\Order;
use App\models\Product;
use App\models\User;

/**
 * Class CartController is responsible cart product manipulations.
 */
class CartController
{
    /**
     * @const ADMIN_EMAIL - administrator email address
     */
    const ADMIN_EMAIL = 'admin@site.dev';
    /**
     * @const ADMIN_MESSAGE - message to administrator
     */
    const ADMIN_MESSAGE = 'Check order list!';
    /**
     * @const ADMIN_SUBJECT - subject of mail
     */
    const ADMIN_SUBJECT = 'New order';

    /**
     * action for adding products to cart.
     *
     * @param int $id - product id
     *
     * @return void
     */
    public function actionAdd($id)
    {
        Cart::addProduct($id); // Adding a product to cart
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer"); // Redirecting user on previous page
    }

    /**
     * action for viewing user cart.
     *
     * @return bool true
     */
    public function actionIndex()
    {
        $categories = [];
        $categories = Category::getCategoriesList();
        $productsInCart = false;
        $productsInCart = Cart::getProducts(); // Receiving information from cart
        if ($productsInCart) {
            $productsIds = array_keys($productsInCart); // Receiving full information about products
            $products = Product::getProductsByIds($productsIds);
            $totalPrice = Cart::getTotalPrice($products); // Receiving total price
        }

        require_once ROOT.'/views/cart/index.php';

        return true;
    }

    /**
     * action for product removal from cart.
     *
     * @param int $id - product id
     *
     * @return void
     */
    public function actionDelete($id)
    {
        Cart::deleteProducts($id); // Product removal from cart
        header('Location: /cart');
    }

    /**
     * action for making a product order.
     *
     * @return bool true
     */
    public function actionCheckout()
    {
        $productsInCart = Cart::getProducts();
        if ($productsInCart === false) { // if cart is empty, redirecting user search products
            header('Location: /');
        }
        $categories = [];
        $categories = Category::getCategoriesList();
        $productsIds = array_keys($productsInCart);
        $products = Product::getProductsByIds($productsIds);
        $totalPrice = Cart::getTotalPrice($products);
        $totalQuantity = Cart::countItems();

        $userName = false;
        $userPhone = false;
        $userComment = false;
        $result = false; // Status of success order

        if (!User::isGuest()) {
            $userId = User::checkLogged(); // User is not guest
            $user = User::getUserById($userId); // Retrieving info about user
            $userName = $user['name'];
        } else {
            $userId = false; // if user is guest, form fields will be empty
        }

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
            $errors = false;

            if (!User::checkName($userName)) {
                $errors[] = 'Неправильное имя';
            }
            if (!User::checkPhone($userPhone)) {
                $errors[] = 'Неправильный формат номера телефона';
            }
            if ($errors === false) {
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart); // Saving an order
                if ($result) {
                    mail(self::ADMIN_EMAIL, self::ADMIN_MESSAGE, self::ADMIN_SUBJECT);
                    Cart::clear(); //Cart cleaning
                }
            }
        }

        require_once ROOT.'/views/cart/checkout.php';

        return true;
    }
}
