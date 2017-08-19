<?php include ROOT.'/views/layouts/header.php'; ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Каталог</h2>
                    <div class="panel-group category-products">
                        <?php foreach ($categories as $categoryItem): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="/category/<?= $categoryItem['id']; ?>">
                                            <?= $categoryItem['name']; ?>
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 padding-right">
                <div class="product-details"><!--product-details-->
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="view-product">
                                <img src="<?= App\models\Product::getImage($product['id']); ?>" alt="" />
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="product-information"><!--/product-information-->

                                <?php if ($product['is_new']): ?>
                                    <img src="/template/images/home/new1.jpg" class="newarrival" alt="" />
                                <?php endif; ?>

                                <h2><?= $product['name']; ?></h2>
                                <p>Код товара: <?= $product['code']; ?></p>
                                <span>
                                <span>US $<?= $product['price']; ?></span>
                                <a href="/cart/add/<?= $product['id']; ?>"
                                   class="btn btn-default add-to-cart">
                                    <i class="fa fa-shopping-cart"></i>В корзину
                                </a>
                            </span>
                                <p><b>Наличие:</b> <?= App\models\Product::getAvailabilityText($product['availability']); ?></p>
                                <p><b>Производитель:</b> <?= $product['brand']; ?></p>
                            </div><!--/product-information-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <br/>
                            <h5>Описание товара</h5>
                            <?= $product['description']; ?>
                        </div>
                    </div>
                </div><!--/product-details-->
            </div>
        </div>
    </div>
</section>
<?php include ROOT.'/views/layouts/footer.php'; ?>
