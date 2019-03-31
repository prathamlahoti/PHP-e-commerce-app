### Introduction
The application was built by the example of my [Yii2-ecommerce](https://github.com/prathamlahoti/Yii2_e-commerce_app) using my custom [PHP-Custom-MVC-Architecture](https://github.com/prathamlahoti/PHP-Custom-MVC-Architecture). It allows users to view products list, using different filters like: product categories, product prices, product brands, etc. Users can add multiple products to cart and place products orders. There are 2 types of permissons: an admin and a user. Admins are fully responsible for the project content and orders managing.

### Deployment
To deploy the application on your machine, you should install MySQL and PHP. We assume, that it's installed in your system, so now we need to follow several steps below:
1. Create your working directory and clone this repo to this directory
2. If composer is not installed on your machine, you have to follow the installation guide from the official [composer](https://getcomposer.org/download/) documentation
3. As soon as you do that, you need to update all the packages, specified in our project composer.json file. Run `composer update` command to run the installation.
4. Include your database configuration to _config/db_params.php_
5. Manually create _.htaccess_ file and connect it with your Apache2.
6. Run server to test, whether you have no errors.

### Usage
 If everything works fine, you are ready to create your first route. The logic of routes lies in creating a route path and its handler. You can create a new route in _config/routes.php_. Following the convention of creating routes, you must create the appropriate controller for a route.
