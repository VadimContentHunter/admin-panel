<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\User\User;
use vadimcontenthunter\AdminPanel\validations\LoginValidate;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\models\Responses\types\ResponseTypeData;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class UserController
{
    public function getUserObjBySession(): ?IUser
    {
        $user_email = $_SESSION["ss_user_email"] ?? '';
        $user_password_hash = $_SESSION["ss_user_password_hash"] ?? '';

        $user = User::selectByEmailAndPassword($user_email, $user_password_hash);

        if (!$user) {
            unset($_SESSION["ss_user_email"]);
            unset($_SESSION["ss_user_password_hash"]);
        } else {
            $_SESSION["ss_user_email"] = $user->getEmail();
            $_SESSION["ss_user_password_hash"] = $user->getPasswordHash();
        }

        return $user;
    }

    /**
     * @param mixed[] $parameters
     */
    public function loginUser(array $parameters): void
    {
        $user_email = $parameters['from_user_email'] ?? $parameters['user_email'] ??  '';
        $user_password = $parameters['from_user_password'] ?? '';
        $user_password_hash = $parameters['from_user_password_hash'] ?? User::composePasswordHash($user_password);

        $user = User::selectByEmailAndPassword($user_email, $user_password_hash);

        if (!$user) {
            unset($_SESSION["ss_user_email"]);
            unset($_SESSION["ss_user_password_hash"]);
            $response = new ResponseTypeData(false, 1, message: 'Ошибка: Неправильный логин или пароль.');
        } else {
            $_SESSION["ss_user_email"] = $user->getEmail();
            $_SESSION["ss_user_password_hash"] = $user->getPasswordHash();
            $response = new ResponseTypeData(true, 0, [
                'redirect' => Helper::getCurrentHostUrl() . '/admin'
            ]);
        }

        echo $response->getResponse()->getJsonFormat();
    }

    /**
     * @param mixed[] $parameters
     */
    public function registerUser(array $parameters): void
    {
        $response = new ResponseTypeData(false, 1);
        $response->setMessage('Не удалось зарегистрироваться.');

        $user_name = $parameters['from_user_name'] ?? '';
        $user_email = $parameters['from_user_email'] ?? '';
        $user_password = $parameters['from_user_password'] ?? '';
        $user_confirm_password = $parameters['from_user_confirm_password'] ?? '';

        $userLoginValidate = new LoginValidate();
        $userLoginValidate->isNameEmpty($user_name, 'result_login', 'Логин не указан.');
        $userLoginValidate->isEmailEmpty($user_email, 'result_email', 'Email не указан.');
        $userLoginValidate->isPasswordEmpty($user_password, 'result_password', 'Пароль не указан.');
        $userLoginValidate->checkPasswordMatchWithConfirmPassword($user_password, $user_confirm_password, 'result_confirm_password', 'Пароли не совпадают');

        if ($userLoginValidate->isExistingMail((new User()), $user_email)) {
            $response->setData($userLoginValidate->getResult());
            $response->setMessage('Пользователь уже существует.');
        }

        if ($userLoginValidate->hasValidating()) {
            $user = new User($user_name, $user_email, $user_password);
            $user->insertObjectToDb();
            $_SESSION["ss_user_email"] = $user->getEmail();
            $_SESSION["ss_user_password_hash"] = $user->getPasswordHash();
            $response->setSuccess(true);
            $response->setMessage('Пользователь добавлен.');
        } else {
            $response->setData($userLoginValidate->getResult());
        }

        echo $response->getResponse()->getJsonFormat();
    }
}
