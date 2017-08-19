<?php

namespace App\models;

/**
 * Class Cart is responsible for validating and manipulations with cart data.
 */
class Cart
{
    /**
     * method for adding new product to user cart.
     *
     * @param int $id - product id
     *
     * @return void
     */
    public static function addProduct($id)
    {
        $id = intval($id);
        $productsInCart = [];
        if (isset($_SESSION['products'])) {
            $productsInCart = $_SESSION['products'];  // Adding products to cart
        }
        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id]++; // unless products is already added to cart, Increasing the count
        } else {
            $productsInCart[$id] = 1; //Adding new product to cart
        }
        $_SESSION['products'] = $productsInCart;
    }

    /**
     * method for counting products in user cart.
     *
     * @return int the number of products in user cart
     */
    public static function countItems()
    {
        if (isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity) {// id is a product id, quantity is count of product
                $count += $quantity; //Total price of all products
            }

            return $count;
        }

        return 0;
    }

    /**
     * method for receiving products from user cart.
     *
     * @return mixed: array|boolean
     */
    public static function getProducts()
    {
        return (isset($_SESSION['products'])) ? $_SESSION['products'] : false;
    }

    /**
     * method for receiving total price of products.
     *
     * @param array $products - products in user cart
     *
     * @return int - total price of all products
     */
    public static function getTotalPrice($products)
    {
        $productsInCart = self::getProducts();
        $total = 0;
        if ($productsInCart) {
            foreach ($products as $item) {
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }

        return $total;
    }

    /**
     * method for cleaning user cart.
     *
     * @return void
     */
    public static function clear()
    {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']);
        }
    }

    /**
     * method for deleting products from user cart.
     *
     * @param int $id - product id
     *
     * @return void
     */
    public static function deleteProducts($id)
    {
        $productsInCart = self::getProducts(); // Receiving an array with ids and count of products in cart
        unset($productsInCart[$id]); // Deleting a product from array
        $_SESSION['products'] = $productsInCart; // Overwriting array with new products
    }
}
