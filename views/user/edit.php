<?php include ROOT.'/views/layouts/header.php'; ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4 padding-right">
                <?php if ($result): ?>
                    <p>Данные изменены.</p>
                <?php else: ?>
                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li> - <?= $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="signup-form"><!--sign up form-->
                        <h2>Редактированые данные </h2>
                        <form action="#" method="post">
                            <input type="hidden" name="csrf_token" value="<?= App\components\Security::generateToken(); ?>">
                            <p>Имя: </p><input type="text" name="name" placeholder="Имя" value="<?= $name; ?>"/>
                            <p>Пароль: </p> <input type="password" name="password" placeholder="Пароль" />
                            <input type="submit" name="submit" class="btn btn-default" value="Сохранить" />
                        </form>
                    </div><!--/sign up form-->
                <?php endif; ?>
                <br/>
                <br/>
            </div>
        </div>
    </div>
</section>
<?php include ROOT.'/views/layouts/footer.php'; ?>
