<?php

namespace App\controllers;

use App\components\Security;
use App\errors\HttpSecurityException;
use App\models\AdminBase;
use App\models\Category;

/**
 * Class AdminCategoryController is responsible for site categories manipulations.
 */
class AdminCategoryController extends AdminBase
{
    /**
     * action for viewing a category page.
     *
     * @return bool true
     */
    public function actionIndex()
    {
        self::checkAdmin();
        $categoriesList = Category::getCategoriesListAdmin();

        require_once ROOT.'/views/admin_category/index.php';

        return true;
    }

    /**
     * action for creating a category.
     *
     * @return bool true
     */
    public function actionCreate()
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
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];
            $errors = false; // errors checker

            if (!isset($name) || empty($name)) {
                $errors[] = 'Заполните поля';
            }
            if ($errors === false) { // unless no mistakes, adding a new category
                Category::createCategory($name, $sortOrder, $status);
                header('Location: /admin/category');
            }
        }

        require_once ROOT.'/views/admin_category/create.php';

        return true;
    }

    /**
     * action for updating category by its id.
     *
     * @param int $id - category id
     *
     * @return bool true
     */
    public function actionUpdate($id)
    {
        self::checkAdmin();
        $category = Category::getCategoryById($id);

        if (isset($_POST['submit']) && isset($_SESSION['csrf_'])) {
            try{
                if (!Security::hashComparison($_SESSION['csrf_'], $_POST['csrf_token'])) {
                    throw new HttpSecurityException("CSRF attack detected");
                }
            } catch (HttpSecurityException $ex) {
                echo $ex->getMessage();
            }
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];
            Category::updateCategoryById($id, $name, $sortOrder, $status);
            header('Location: /admin/category');
        }

        require_once ROOT.'/views/admin_category/update.php';

        return true;
    }

    /**
     * action for updating category by its id.
     *
     * @param int $id - category id
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
            Category::deleteCategoryById($id);
            header('Location: /admin/category');
        }

        require_once ROOT.'/views/admin_category/delete.php';

        return true;
    }
}
