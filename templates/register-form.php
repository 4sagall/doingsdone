<div class="content">
    <section class="content__side">
        <p class="content__side-info">Сервис «Дела в Порядке» <br>доступен для использования <br>только зарегистрированным пользователям.</p>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Регистрация аккаунта</h2>

        <form class="form" action="register.php" method="post" autocomplete="off">
            <div class="form__row">
                <label class="form__label" for="email">E-mail <sup>*</sup></label>

                <?php $classname = (isset($errors['email'])) ? " form__input--error" : ""; ?>
                <input class="form__input <?= $classname; ?>" type="text" name="email" id="email"
                       value="<?= getPostVal('email'); ?>" placeholder="Введите e-mail">
                <p class="form__message"><?= $errors['email'] ?? ""; ?></p>
            </div>

            <div class="form__row">
                <label class="form__label" for="password">Пароль <sup>*</sup></label>

                <?php $classname = (isset($errors['password'])) ? " form__input--error" : ""; ?>
                <input class="form__input <?= $classname; ?>" type="password" name="password" id="password"
                       value="<?= getPostVal('password'); ?>" placeholder="Введите пароль">
                <p class="form__message"><?= $errors['password'] ?? ""; ?></p>
            </div>

            <div class="form__row">
                <label class="form__label" for="name">Имя <sup>*</sup></label>

                <?php $classname = (isset($errors['name'])) ? " form__input--error" : ""; ?>
                <input class="form__input <?= $classname; ?>" type="text" name="name" id="name"
                       value="<?= getPostVal('name'); ?>" placeholder="Введите имя">
                <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
            </div>

            <div class="form__row form__row--controls">
                <p class="error-message"><?= (!empty($errors)) ? "Пожалуйста, исправьте ошибки в форме" : ""; ?></p>

                <input class="button" type="submit" name="" value="Зарегистрироваться">
            </div>
        </form>
    </main>
</div>
