<?php include ROOT.'/views/layouts/header_admin.php'; ?>
<section>
    <div class="container">
        <div class="row">
            <br />
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href ="/admin">Админпанель</a></li>
                    <li class="active">Управление товарами</li>
                    <li class="active">Удалить товар</li>
                </ol>
            </div>

            <h4>Удалить товар № <?= $id; ?></h4>
            <br />
      <p>Вы действительно хотите удалить товар? </p>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= App\components\Security::generateToken(); ?>">
                <input type = 'submit' name = 'submit' value = 'Удалить' />
            </form>
         </div>
    </div>
</section>
<?php include ROOT.'/views/layouts/footer_admin.php'; ?>
