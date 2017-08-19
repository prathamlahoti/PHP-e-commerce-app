<?php

namespace App\controllers;

use App\models\AdminBase;

/**
 * Class AdminController is responsible for viewing admin panel.
 */
class AdminController extends AdminBase
{
    /**
     * action for checking access to admin panel.
     *
     * @return bool true
     */
    public function actionIndex()
    {
        self::checkAdmin(); // Checking admin access

        require_once ROOT.'/views/admin/index.php';

        return true;
    }
}
