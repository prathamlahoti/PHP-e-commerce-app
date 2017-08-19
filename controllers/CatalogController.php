<?php

namespace App\controllers;

use App\components\Pagination;
use App\models\Category;
use App\models\Product;

/**
 * Class CatalogController is responsible for checking product list.
 */
class CatalogController
{
    /**
     * action for checking pages with products.
     *
     * @param int $page - number of particular page of list
     *
     * @return bool true
     */
    public function actionIndex($page = 1)
    {
        $categories = Category::getCategoriesList();
        $latestProducts = Product::getAllProducts($page);
        $total = Product::getTotalProduct();
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        require_once ROOT.'/views/catalog/index.php';

        return true;
    }

    /**
     * action for checking pages with products by category.
     *
     * @param int $categoryId - category id
     * @param int $page       - number of particular page of list
     *
     * @return bool true
     */
    public function actionCategory($categoryId, $page = 1)
    {
        $categories = Category::getCategoriesList();
        $categoryProducts = Product::getProductsListByCategory($categoryId, $page);
        $total = Product::getTotalProductsInCategory($categoryId);
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        require_once ROOT.'/views/catalog/category.php';

        return true;
    }

    public function actionRecommended($page = 1)
    {
        $categories = Category::getCategoriesList();
        $latestProducts = Product::getRecommendedProducts($page);
        $total = Product::getTotalRecommendedProduct();
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        require_once ROOT.'/views/catalog/recommended.php';

        return true;
    }

    public function actionNew($page = 1)
    {
        $categories = Category::getCategoriesList();
        $latestProducts = Product::getNewProducts($page);
        $total = Product::getTotalNewProduct();
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        require_once ROOT.'/views/catalog/recommended.php';

        return true;
    }
}
