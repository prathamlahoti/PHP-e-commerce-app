<?php

namespace App\controllers;

use App\components\Security;
use App\errors\HttpSecurityException;
use App\models\AdminBase;
use App\models\Category;
use App\models\Product;

/**
 * Class AdminProductController is responsible for products manipulations.
 */
class AdminProductController extends AdminBase
{
    /**
     * action for checking products page with its admin features.
     *
     * @return bool true
     */
    public function actionIndex()
    {
        self::checkAdmin();
        $productsList = Product::getProductsList();

        require_once ROOT.'/views/admin_product/index.php';

        return true;
    }

    /**
     * action for creating a new product.
     *
     * @return bool true
     */
    public function actionCreate()
    {
        self::checkAdmin();
        $categoriesList = Category::getCategoriesListAdmin(); // Receiving categories list for drop down list

        if (isset($_POST['submit']) && isset($_SESSION['csrf_'])) {
            try{
                if (!Security::hashComparison($_SESSION['csrf_'], $_POST['csrf_token'])) {
                    throw new HttpSecurityException("CSRF attack detected");
                }
            } catch (HttpSecurityException $ex) {
                echo $ex->getMessage();
            }
            $options = []; // array for recording POST data
            $options['name'] = $_POST['name'];
            $options['category_id'] = intval($_POST['category_id']);
            $options['code'] = intval($_POST['code']);
            $options['price'] = intval($_POST['price']);
            $options['availability'] = intval($_POST['availability']);
            $options['brand'] = $_POST['brand'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = intval($_POST['is_new']);
            $options['is_recommended'] = intval($_POST['is_recommended']);
            $options['status'] = intval($_POST['status']);
            $errors = false;  // Errors checker

            if (!isset($options['name']) || empty($options['name'])) {
                $errors[] = 'Заполните поля';
            }

            if ($errors === false) {
                $id = Product::createProduct($options);  // Adding a new product
                if ($id) { // if a product is added
                    if (is_uploaded_file($_FILES['image']['tmp_name'])) {  // Checking for uploading image through the form
                       // if true, then move it in a necessary directory with new name
                        move_uploaded_file($_FILES['image']['tmp_name'], ROOT.'/upload/images/products/'.Security::generateFileName($id));
                    }
                }
                header('Location: /admin/product');
            }
        }

        require_once ROOT.'/views/admin_product/create.php';

        return true;
    }

    /**
     * action for updating a product by its id.
     *
     * @param int $id - product id
     *
     * @return bool true
     */
    public function actionUpdate($id)
    {
        self::checkAdmin();
        $categoriesList = Category::getCategoriesListAdmin();
        $product = Product::getProductById($id); //Receiving information about particular product

        if (isset($_POST['submit']) && isset($_SESSION['csrf_'])) {
            try{
                if (!Security::hashComparison($_SESSION['csrf_'], $_POST['csrf_token'])) {
                    throw new HttpSecurityException("CSRF attack detected");
                }
            } catch (HttpSecurityException $ex) {
                echo $ex->getMessage();
            }
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            if (Product::updateProductById($id, $options)) {  // unless an entry is saved
                if (is_uploaded_file($_FILES['image']['tmp_name'])) { // Checking for uploading image through the form
                    // if true, then move it in a necessary directory with new name
                    move_uploaded_file($_FILES['image']['tmp_name'], ROOT.'/upload/images/products/'.Security::generateFileName($id));
                }
            }

            header('Location: /admin/product');
        }

        require_once ROOT.'/views/admin_product/update.php';

        return true;
    }

    /**
     * action for product removal by its id.
     *
     * @param $id - product id
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
            Product::deleteProductById($id);  // Product removal
            header('Location: /admin/product');
        }

        require_once ROOT.'/views/admin_product/delete.php';

        return true;
    }
}
