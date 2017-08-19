<?php

namespace App\models;

use App\components\Db;
use App\components\Security;
use PDO;

/**
 * Class Product is responsible for validating and manipulations with product data.
 */
class Product
{
    /**
     * @const SHOW_BY_DEFAULT - how many products we'd like to see in the product catalog
     */
    const SHOW_BY_DEFAULT = 6; // Number of products displayed by default

    const PRODUCT_STATUS = 1;

    const PRODUCT_AVAILABILITY = 1;

    const PRODUCT_IS_NEW = 1;

    const PRODUCT_IS_RECOMMENDED = 1;

    /**
     * method for retrieving recently added products.
     *
     * @param int $count        - number of products
     * @param int $status       - shows product status
     * @param int $availability - shows whether product is available
     * @param int $is_new       - shows whether product is new
     *
     * @return array - list of retrieved products
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT, $status = self::PRODUCT_STATUS, $availability = self::PRODUCT_AVAILABILITY, $is_new = self::PRODUCT_IS_NEW)
    {
        $db = Db::getConnection();
        $sql = "SELECT id, name, price, is_new FROM product WHERE status=:status AND availability=:availability AND is_new=:is_new ORDER BY id DESC LIMIT {$count}";
        $result = $db->prepare($sql);
        $result->execute([
            ':status'       => $status,
            ':availability' => $availability,
            ':is_new'       => $is_new,
        ]);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for retrieving the full products list.
     *
     * @param int $page   - number of particular page for pagination
     * @param int $status - number of products
     *
     * @return array - list of products
     */
    public static function getAllProducts($page = 1, $status = self::PRODUCT_STATUS)
    {
        $page = intval($page);
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        $limit = self::SHOW_BY_DEFAULT;
        $db = Db::getConnection();
        $sql = 'SELECT id, name, price, is_new FROM product
                WHERE status=:status
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset';
        $result = $db->prepare($sql);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for receiving number of products.
     *
     * @param $status - product status
     *
     * @return int - number of products
     */
    public static function getTotalProduct($status = self::PRODUCT_STATUS)
    {
        $db = Db::getConnection();
        $sql = 'SELECT count(id) AS count FROM product WHERE status=:status ';
        $result = $db->prepare($sql);
        $result->execute([':status' => $status]);

        return $result->fetch()['count'];
    }

    /**
     * method for receiving number of recommended products.
     *
     * @param $status - product status
     * @param $recommended - shows whether product is recommended
     *
     * @return int - number of products
     */
    public static function getTotalRecommendedProduct($status = self::PRODUCT_STATUS, $recommended = self::PRODUCT_IS_RECOMMENDED)
    {
        $db = Db::getConnection();
        $sql = 'SELECT count(id) AS count FROM product WHERE status=:status AND is_recommended=:recommended';
        $result = $db->prepare($sql);
        $result->execute([':status' => $status, ':recommended' => $recommended]);

        return $result->fetch()['count'];
    }

    /**
     * method for receiving number of recommended products.
     *
     * @param $status - product status
     * @param $is_new - shows whether product is new
     *
     * @return int - number of products
     */
    public static function getTotalNewProduct($status = self::PRODUCT_STATUS, $is_new = self::PRODUCT_IS_NEW)
    {
        $db = Db::getConnection();
        $sql = 'SELECT count(id) AS count FROM product WHERE status=:status AND is_new=:is_new';
        $result = $db->prepare($sql);
        $result->execute([':status' => $status, ':is_new' => $is_new]);

        return $result->fetch()['count'];
    }

    /**
     * method for retrieving products list by its category.
     *
     * @param bool $categoryId - category id
     * @param int  $page       - number of particular page for pagination
     * @param int  $status     - shows product status
     *
     * @return array - list of products
     */
    public static function getProductsListByCategory($categoryId = false, $page = 1, $status = self::PRODUCT_STATUS)
    {
        if ($categoryId) {
            $page = intval($page);
            $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        }
        $limit = self::SHOW_BY_DEFAULT;
        $db = Db::getConnection();
        $sql = 'SELECT id, name, price, is_new FROM product
                WHERE status=:status AND category_id=:category_id 
                ORDER BY id ASC
               LIMIT :limit OFFSET :offset';

        $result = $db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for retrieving particular product by its id.
     *
     * @param int $id - product id
     *
     * @return array - information about product
     */
    public static function getProductById($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM product WHERE id=:id';
        $result = $db->prepare($sql);
        $result->execute([':id' => $id]);

        return $result->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * method for receiving the number of products in the category.
     *
     * @param int $categoryId - category id
     * @param int $status     - shows product status
     *
     * @return int - number of products
     */
    public static function getTotalProductsInCategory($categoryId, $status = self::PRODUCT_STATUS)
    {
        $db = Db::getConnection();
        $sql = 'SELECT count(id) AS count FROM product WHERE status=:status AND category_id=:category_id';
        $result = $db->prepare($sql);
        $result->execute([
            ':category_id' => $categoryId,
            ':status'      => $status,
        ]);

        return $result->fetch()['count'];
    }

    /**
     * method for retrieving products by its ids.
     *
     * @param array $idsArray - product ids
     * @param int   $status   - shows product status
     *
     * @return array - list of products
     */
    public static function getProductsByIds($idsArray, $status = self::PRODUCT_STATUS)
    {
        $db = Db::getConnection();
        $idsString = implode(',', $idsArray); // Converting array to string for query expression
        $sql = "SELECT * FROM product WHERE status=:status AND id IN ($idsString)";
        $result = $db->prepare($sql);
        $result->execute([':status' => $status]);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for retrieving recommended products.
     *
     * @param int $page        - number of particular page for pagination
     * @param int $recommended - shows whether product is recommended
     *
     * @return array - list of recommended products
     */
    public static function getRecommendedProducts($page = 1, $recommended = self::PRODUCT_IS_RECOMMENDED)
    {
        $page = intval($page);
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        $limit = self::SHOW_BY_DEFAULT;
        $db = Db::getConnection();
        $sql = 'SELECT id, name, price, is_new FROM product
                WHERE is_recommended=:recommended
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset';
        $result = $db->prepare($sql);
        $result->bindParam(':recommended', $recommended, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for retrieving new products.
     *
     * @param int $page - number of particular page for pagination
     * @param int $new  - shows whether product is new
     *
     * @return array - list of new products
     */
    public static function getNewProducts($page = 1, $new = self::PRODUCT_IS_NEW)
    {
        $page = intval($page);
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        $limit = self::SHOW_BY_DEFAULT;
        $db = Db::getConnection();
        $sql = 'SELECT id, name, price, is_new FROM product
                 WHERE is_new=:is_new
                 ORDER BY id DESC
                 LIMIT :limit OFFSET :offset';


        $result = $db->prepare($sql);
        $result->bindParam(':is_new', $new, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * method for retrieving products list.
     *
     * @return array - products list
     */
    public static function getProductsList()
    {
        $db = Db::getConnection();
        $sql = 'SELECT id, name, code, price FROM product ORDER BY id ASC';

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id - product id
     *
     * @return bool - result of deleting a product
     */
    public static function deleteProductById($id)
    {
        $db = Db::getConnection();
        $sql = 'DELETE FROM product WHERE id=:id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $img = ROOT.'/upload/images/products/'.Security::generateFileName($id);
        if (file_exists($img)) {
            unlink($img);

            return $result->execute();
        }

        return false;
    }

    /**
     * method for updating product by its id.
     *
     * @param int   $id      - product id
     * @param array $options - product params
     *
     * @return bool - the result of updating a product
     */
    public static function updateProductById($id, $options)
    {
        $db = Db::getConnection();
        $sql = 'UPDATE product SET 
            name=:name, code=:code, price=:price, 
            category_id=:category_id, brand=:brand, availability=:availability, 
            description=:description, is_new=:is_new, is_recommended=:is_recommended, status=:status
                WHERE id=:id';

        return $db->prepare($sql)->execute([
            ':id'             => $id,
            ':name'           => $options['name'],
            ':code'           => $options['code'],
            ':price'          => $options['price'],
            ':category_id'    => $options['category_id'],
            ':brand'          => $options['brand'],
            ':availability'   => $options['availability'],
            ':description'    => $options['description'],
            ':is_new'         => $options['is_new'],
            ':is_recommended' => $options['is_recommended'],
            ':status'         => $options['status'],
        ]);
    }

    /**
     * method for product creating.
     *
     * @param array $options - product params
     *
     * @return int - product id or 0
     */
    public static function createProduct($options)
    {
        $db = Db::getConnection();$sql = 'INSERT INTO product (name, category_id, code, price, availability, brand,description, is_new, is_recommended, status)
                VALUES (:name, :category_id, :code, :price, :availability, :brand, :description, :is_new, :is_recommended, :status)';
        $result = $db->prepare($sql);

        return ($result->execute([
            ':name'           => $options['name'],
            ':category_id'    => $options['category_id'],
            ':code'           => $options['code'],
            ':price'          => $options['price'],
            ':availability'   => $options['availability'],
            ':brand'          => $options['brand'],
            ':description'    => $options['description'],
            ':is_new'         => $options['is_new'],
            ':is_recommended' => $options['is_recommended'],
            ':status'         => $options['status'],
        ])) ? $db->lastInsertId() : 0; // unless query is successfully executed, returning id of added record, else 0
    }

    /**
     * method for checking product status.
     *
     * @param bool $availability - product status
     *
     * @return string - value of product status
     */
    public static function getAvailabilityText($availability)
    {
        switch ($availability) {
            case 0:
                return 'Под заказ';
            case 1:
                return 'В наличии';
        }
    }

    /**
     * method for receiving product image by image path.
     *
     * @param int $id - product id
     *
     * @return string - image path
     */
    public static function getImage($id)
    {
        $noImage = 'no-image.jpg'; // the name of empty image
        $path = '/upload/images/products/'; // Path to folder with products
        $image = Security::generateFileName($id);
        $pathToProductImage = $path.$image; // Path to the product image
        $fullPath = ROOT.$pathToProductImage;

        // unless product image exists, returning the path of product image. Else returning the path of empty image
        return (file_exists($fullPath)) ? $pathToProductImage : $path.$noImage;
    }
}
