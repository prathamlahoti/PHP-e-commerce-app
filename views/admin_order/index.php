<?php include ROOT.'/views/layouts/header_admin.php'; ?>
<section>
    <div class="container">
        <div class="row">
            <br />
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href ="/admin">Админпанель</a></li>
                    <li class="active">Управление заказами</li>
                </ol>
            </div>
            <h4>Список заказов</h4>
            <br />
            <table class="table-bordered table-striped table">
                <tr>
                    <th>ID заказа</th>
                    <th>Имя покупателя</th>
                    <th>Телефон покупателя</th>
                    <th>Дата оформления</th>
                    <th>Статус</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php foreach($ordersList as $order): ?>
                    <tr>
                        <td><?= $order['id']; ?></td>
                        <td><?= $order['user_name']; ?></td>
                        <td><?= $order['user_phone']; ?></td>
                        <td><?= $order['date']; ?></td>
                        <td><?= App\models\Order::getStatusText($order['status']); ?></td>

                        <td><a href="/admin/order/view/<?= $order['id']; ?>">Смотреть</a></td>
                        <td><a href="/admin/order/update/<?= $order['id']; ?>">Редактировать</a></td>
                        <td><a href="/admin/order/delete/<?= $order['id']; ?>">Удалить</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>
<?php include ROOT.'/views/layouts/footer_admin.php'; ?>
