<?php

$css_paths ??= [];
$js_head_paths ??= [];
$js_begin_body_paths ??= [];
$js_after_body_paths ??= [];

$login_action ??= '';
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
            <form action='<?= $login_action ?>'>
                <ul>
                    <li>
                        <label >Электронная почта</label>
                        <input type="email" name="email" placeholder="Введите электронную почту" value="<?= $email ?>">
                        <output name="result_email"></output>
                    </li>
                    <li>
                        <label >Пароль</label>
                        <input type="password" name="password" placeholder="Введите пароль" value="<?= $password ?>">
                        <output name="result_password"></output>
                    </li>
                </ul>
                <div class="panel-footer-form">
                    <input type="submit" value="Продолжить">
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
            <form>
                <ul>
                    <li>
                        <label >Имя пользователя</label>
                        <input type="text" name="login" placeholder="Введите имя пользователя">
                        <output name="result_login"></output>
                    </li>
                    <li>
                        <label >Электронная почта</label>
                        <input type="email" name="email" placeholder="Введите электронную почту">
                        <output name="result_email"></output>
                    </li>
                    <li>
                        <label >Пароль</label>
                        <input type="password" name="password" placeholder="Введите пароль">
                        <output name="result_password"></output>
                    </li>
                    <li>
                        <label >Повторите пароль</label>
                        <input type="password" name="confirm_password" placeholder="Повторите пароль">
                        <output name="result_confirm_password"></output>
                    </li>
                </ul>
                <div class="panel-footer-form">
                    <input type="submit" value="Продолжить">
                </div>
            </form>

            <div class="panel-footer">
                <a>Уже зарегистрированные? Тогда войдите в свой профиль. <span>Войти</span></a>
            </div>
        </div>
    </main>

    <?php foreach ($js_after_body_paths as $path) : ?>
        <script src="<?= $path ?>"></script>
    <?php endforeach; ?>
</body>

<script>
    setClickHandlerOnElem('#container_sing_in .panel-footer a', (elem_a) => {
        let elem_register = document.querySelector('#container_register') ?? null;
        if (elem_register === null) {
            throw new Error('Error!');
        }
        elem_register.style.display = 'block';

        let elem_parent = elem_a.closest('.login-container');
        elem_parent.style.display = 'none';
    });

    setClickHandlerOnElem('#container_register .panel-footer a', (elem_a) => {
        let elem_sing_in = document.querySelector('#container_sing_in') ?? null;
        if (elem_sing_in === null) {
            throw new Error('Error!');
        }
        elem_sing_in.style.display = 'block';

        let elem_parent = elem_a.closest('.login-container');
        elem_parent.style.display = 'none';
    });
</script>
<script>
    setClickHandlerOnForm('#container_sing_in form', (elem_form) => {
        serverRequest(
            elem_form.getAttribute('action'),
            (new FormData(elem_form)),
            function (response_data) {
                let elem_output = elem_form.querySelector('output[name="result_email"]') ?? null;
                if (elem_output === null) {
                    throw new Error('Error!');
                }

                if(typeof response_data?.message !== 'undefined' && response_data.message !== ''){
                    elem_output.innerHTML = response_data.message ?? '';
                    elem_output.style.display = 'block';
                }

                if(typeof response_data?.data?.redirect !== 'undefined'){
                    location= response_data.data.redirect;
                }
            }
        )
    });
</script>

</html>
