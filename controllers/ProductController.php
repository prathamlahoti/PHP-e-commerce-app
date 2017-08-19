<?php

namespace App\controllers;

use App\models\Category;
use App\models\Product;

/**
 * Class ProductController is responsible for checking particular product.
 */
class ProductController
{
    /**
     * @param int $productId - product id
     *
     * @return bool true
     */
    public function actionView($productId)
    {
        $categories = Category::getCategoriesList();
        $product = Product::getProductById($productId);

        require_once ROOT.'/views/product/view.php';

        return true;
    }
}
