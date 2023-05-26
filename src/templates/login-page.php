<?php

$css_paths ??= [];
$js_head_paths ??= [];
$js_begin_body_paths ??= [];
$js_after_body_paths ??= [];

$login_action ??= '';
$login_json_rpc_method ??= '';

$sing_in_action ??= '';
$sing_in_json_rpc_method ??= '';

$email ??= '';
$password ??= '';
$head ??= '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $head ?>
</head>
<body>
    <?php foreach ($js_begin_body_paths as $path) : ?>
        <script src="<?= $path ?>"></script>
    <?php endforeach; ?>
    <main>
        <div class="login-container" id="container_sing_in">
            <div class="panel-header">
                <h4>Войти</h4>
            </div>
            <form action="<?= $login_action ?>" json-rpc-method="<?= $login_json_rpc_method ?>">
                <ul>
                    <li>
                        <label >Электронная почта</label>
                        <input type="email" name="from_user_email" placeholder="Введите электронную почту" value="<?= $email ?>">
                        <output name="result_email"></output>
                    </li>
                    <li>
                        <label >Пароль</label>
                        <input type="password" name="from_user_password" placeholder="Введите пароль" value="<?= $password ?>">
                        <output name="result_password"></output>
                    </li>
                </ul>
                <div class="panel-footer-form">
                    <input type="button" value="Продолжить">
                    <a>Забыли пароль ?</a>
                </div>
            </form>

            <div class="panel-footer">
                <a>Вы можете зарегистрироваться. <span>Зарегистрироваться</span></a>
            </div>
        </div>

        <div class="login-container" style="display: none;" id="container_register">
            <div class="panel-header">
                <h4>Зарегистрироваться</h4>
            </div>
            <form action="<?= $sing_in_action ?>" json-rpc-method="<?= $sing_in_json_rpc_method ?>">
                <ul>
                    <li>
                        <label >Имя пользователя</label>
                        <input type="text" name="from_user_name" placeholder="Введите имя пользователя">
                        <output name="result_login"></output>
                    </li>
                    <li>
                        <label >Электронная почта</label>
                        <input type="email" name="from_user_email" placeholder="Введите электронную почту">
                        <output name="result_email"></output>
                    </li>
                    <li>
                        <label >Пароль</label>
                        <input type="password" name="from_user_password" placeholder="Введите пароль">
                        <output name="result_password"></output>
                    </li>
                    <li>
                        <label >Повторите пароль</label>
                        <input type="password" name="from_user_confirm_password" placeholder="Повторите пароль">
                        <output name="result_confirm_password"></output>
                    </li>
                </ul>
                <div class="panel-footer-form">
                    <input type="button" value="Продолжить">
                </div>
            </form>

            <div class="panel-footer">
                <a>Уже зарегистрированные? Тогда войдите в свой профиль. <span>Войти</span></a>
            </div>
        </div>
    </main>

    <?php foreach ($js_after_body_paths as $path) : ?>
        <script src="<?= $path ?>" type="module"></script>
    <?php endforeach; ?>
</body>
</html>
