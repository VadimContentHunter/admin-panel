<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\User\User;
use vadimcontenthunter\AdminPanel\validations\LoginValidate;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class UserController
{
    public static function getUserObjBySession(): ?IUser
    {
        $user = User::createObjectFromSession();
        $user = User::selectByEmailAndPasswordHash($user->getEmail(), $user->getPasswordHash());
        if (!$user) {
            User::deleteSessionData();
            return null;
        }

        $user->setSessionFromObject();
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

        $user = User::selectByEmailAndPasswordHash($user_email, $user_password_hash);

        if (!$user) {
            User::deleteSessionData();
            $response = new JsonRpcResponse(error: new JsonRpcError(2, 'Неправильный логин или пароль.'), id: $parameters['request_id'] ?? null);
        } else {
            $user->setSessionFromObject();
            $response = new JsonRpcResponse(
                [
                    'redirect' => Helper::getCurrentHostUrl() . '/admin'
                ],
                $parameters['request_id'] ?? null
            );
        }

        echo $response->getJsonRequest();
    }

    /**
     * @param mixed[] $parameters
     */
    public function registerUser(array $parameters): void
    {
        $response = new JsonRpcResponse(error: new JsonRpcError(3, 'Не удалось зарегистрироваться.'));

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
            $response = new JsonRpcResponse(error: new JsonRpcError(
                4,
                'Пользователь уже существует.',
                $userLoginValidate->getResult()
            ), id: $parameters['request_id'] ?? null);
        } elseif ($userLoginValidate->hasValidating()) {
            $user = new User($user_name, $user_email);
            $user->setPasswordHashFromPassword($user_password);
            $user->insertObjectToDb();
            $user->setSessionFromObject();
            $response = new JsonRpcResponse(['alert' => 'Пользователь добавлен.'], $parameters['request_id'] ?? null);
        } else {
            $response = new JsonRpcResponse(error: new JsonRpcError(
                5,
                'Ошибка при регистрации.',
                $userLoginValidate->getResult()
            ), id: $parameters['request_id'] ?? null);
        }

        echo $response->getJsonRequest();
    }
}
