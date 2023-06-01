<?php

    $title ??= '';
    $user_name ??= '';
    $user_email ??= '';
    $password_value ??= '************';
?>
<div class="panel-header">
    <h4><?= $title ?></h4>
    <a class="icon-panel">
        <i></i>
    </a>
</div>
<form>
    <ul>
        <li>
            <label >Имя пользователя</label>
            <input type="text" name="name" placeholder="Введите имя пользователя" value="<?= $user_name ?>" readonly>
            <!-- <output name="result_login"></output> -->
        </li>
        <li>
            <label >Электронная почта</label>
            <input type="email" name="email" placeholder="Введите электронную почту" value="<?= $user_email ?>" readonly>
            <!-- <output name="result_email"></output> -->
        </li>
        <li class="hidden" hidden>
            <label >Новая Электронная почта</label>
            <input type="email" name="new_email" placeholder="Введите новую почту" readonly>
            <!-- <output name="result_new_email"></output> -->
        </li>
        <li>
            <label >Текущий пароль</label>
            <input type="password" name="current_password" placeholder="Введите текущий пароль" readonly>
            <!-- <output name="result_name"></output> -->
        </li>
        <li class="hidden" hidden>
            <label >Новый пароль</label>
            <input type="password" name="new_password" placeholder="Введите новый пароль" readonly>
            <!-- <output name="result_name"></output> -->
        </li>
        <li class="hidden" hidden>
            <label >Повторите новый пароль</label>
            <input type="password" name="confirm_new_password" placeholder="Повторите новый пароль" readonly>
            <!-- <output name="result_name"></output> -->
        </li>
    </ul>
    <div class="panel-footer">
        <input type="button" value="Сохранить">
    </div>
</form>
