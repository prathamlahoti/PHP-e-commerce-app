<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'App\\components\\Db' => $baseDir . '/components/Db.php',
    'App\\components\\Pagination' => $baseDir . '/components/Pagination.php',
    'App\\components\\Router' => $baseDir . '/components/Router.php',
    'App\\components\\Security' => $baseDir . '/components/Security.php',
    'App\\errors\\ErrorHandler' => $baseDir . '/errors/ErrorHandler.php',
    'App\\errors\\FileNotFoundException' => $baseDir . '/errors/FileNotFoundException.php',
    'App\\errors\\HttpSecurityException' => $baseDir . '/errors/HttpSecurityException.php',
    'App\\errors\\PdoDbException' => $baseDir . '/errors/PdoDbException.php',
    'App\\errors\\ResourceNotFoundException' => $baseDir . '/errors/ResourceNotFoundException.php',
    'App\\models\\AdminBase' => $baseDir . '/models/AdminBase.php',
    'App\\models\\Cart' => $baseDir . '/models/Cart.php',
    'App\\models\\Category' => $baseDir . '/models/Category.php',
    'App\\models\\Order' => $baseDir . '/models/Order.php',
    'App\\models\\Product' => $baseDir . '/models/Product.php',
    'App\\models\\User' => $baseDir . '/models/User.php',
);
