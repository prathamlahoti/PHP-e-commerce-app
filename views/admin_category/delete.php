<?php include ROOT .'/views/layouts/header_admin.php'; ?>
<section>
    <div class="container">
        <div class="row">
            <br/>
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="/admin">Админпанель</a></li>
                    <li><a href="/admin/category">Управление категориями</a></li>
                    <li class="active">Удалить категорию</li>
                </ol>
            </div>

            <h4>Удалить категорию № <?= $id; ?></h4>
            <p>Вы действительно хотите удалить эту категорию?</p>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= App\components\Security::generateToken(); ?>">
                <input type="submit" name="submit" value="Удалить" />
            </form>

        </div>
    </div>
</section>
<?php include ROOT .'/views/layouts/footer_admin.php'; ?>

