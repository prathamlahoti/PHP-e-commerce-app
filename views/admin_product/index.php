<?php include ROOT.'/views/layouts/header_admin.php'; ?>
<section>
    <div class="container">
        <div class="row">
            <br />
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href ="/admin">Админпанель</a></li>
                    <li class="active">Управление товарами</li>
                </ol>
            </div>
             <a href = "/admin/product/create" class="btn btn-default back"><i class="fa fa-plus"></i>Добавить товар</a>
            <h4>Список товаров</h4>
            <br />
            <table class="table-bordered table-striped table">
                <tr>
                    <th>ID товара</th>
                    <th>Артикул</th>
                    <th>Название товара</th>
                    <th>Цена</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php foreach($productsList as $product): ?>
                <tr>
                    <td><?= $product['id']; ?></td>
                    <td><?= $product['code']; ?></td>
                    <td><?= $product['name']; ?></td>
                    <td><?= $product['price']; ?></td>
                    <td><a href = "/admin/product/update/<?= $product['id']; ?>">Редактировать</a></td>
                    <td><a href = "/admin/product/delete/<?= $product['id']; ?>"> || Удалить</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>
<?php include ROOT.'/views/layouts/footer_admin.php'; ?>
