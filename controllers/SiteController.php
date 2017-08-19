<?php

namespace App\controllers;

use App\models\Category;
use App\models\Product;

/**
 * Class SiteController is responsible for the main page of site.
 */
class SiteController
{
    /**
     * @const PRODUCTS_COUNT shows how many products will be appeared on the main page
     */
    const PRODUCTS_COUNT = 9;

    /**
     * action for viewing the main page of site.
     *
     * @return bool true
     */
    public function actionIndex()
    {
        $categories = Category::getCategoriesList();
        $latestProducts = Product::getLatestProducts(self::PRODUCTS_COUNT);

        require_once ROOT.'/views/site/index.php';

        return true;
    }

    /**
     * action for viewing About page.
     *
     * @return bool true
     */
    public function actionAbout()
    {
        require_once ROOT.'/views/site/about.php';

        return true;
    }

    /**
     * action for viewing Contacts page.
     *
     * @return bool true
     */
    public function actionContacts()
    {
        require_once ROOT.'/views/site/contacts.php';

        return true;
    }

    /**
     * action for viewing FAQ page.
     *
     * @return bool true
     */
    public function actionFaq()
    {
        require_once ROOT.'/views/site/faq.php';

        return true;
    }
}
