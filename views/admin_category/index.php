<?php include ROOT .'/views/layouts/header_admin.php'; ?>
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
            <a href = "/admin/category/create" class="btn btn-default back"><i class="fa fa-plus"></i>Добавить категорию</a>
            <h4>Список категорий</h4>
            <br />
            <table class="table-bordered table-striped table">
                <tr>
                    <th>ID категории</th>
                    <th>Название категории</th>
                    <th>Порядковый номер</th>
                    <th>Статус</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php foreach($categoriesList as $category): ?>
                    <tr>
                        <td><?= $category['id']; ?></td>
                        <td><?= $category['name']; ?></td>
                        <td><?= $category['sort_order']; ?></td>
                        <td><?= App\models\Category::getStatusText($category['status']); ?></td>

                        <td><a href = "/admin/category/update/<?= $category['id']; ?>" > Изменить </a></td>
                        <td><a href = "/admin/category/delete/<?= $category['id']; ?>"> Удалить </a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>
<?php include ROOT .'/views/layouts/footer_admin.php'; ?>
