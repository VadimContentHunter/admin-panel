<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\controllers;

use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\services\Helper;
use vadimcontenthunter\AdminPanel\models\User\User;
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
        $user_name = $parameters['from_user_name'] ?? '';
        $user_email = $parameters['from_user_email'] ?? '';
        $user_password = $parameters['from_user_password'] ?? '';
        $user_confirm_password = $parameters['from_user_confirm_password'] ?? '';

        $error_data = [];
        $success = true;

        if ($user_name === '') {
            $error_data += [
                    'result_login' => 'Логин не указан.',
            ];
            $success = false;
        }

        if ($user_email === '') {
            $error_data += [
                    'result_email' => 'Email не указан.',
            ];
            $success = false;
        }

        if ($user_password === '') {
            $error_data += [
                    'result_password' => 'Пароль не указан.',
            ];
            $success = false;
        }

        if (strcmp($user_password, $user_confirm_password)) {
            $error_data += [
                'result_confirm_password' => 'Пароли не совпадают',
                'password' => 'Пароли не совпадают',
            ];
            $success = false;
        }


        if (!$success) {
            $response = new ResponseTypeData(
                $success,
                1,
                $error_data,
                'Не удалось зарегистрироваться.'
            );
        } else {
            if (User::selectByEmail($user_email) === null) {
                $user = new User($user_name, $user_email, $user_password);
                $user->insertObjectToDb();
                $_SESSION["ss_user_email"] = $user->getEmail();
                $_SESSION["ss_user_password_hash"] = $user->getPasswordHash();

                $response = new ResponseTypeData(
                    true,
                    0,
                    [
                        'redirect' => Helper::getCurrentHostUrl() . '/admin'
                    ],
                    'Пользователь добавлен.'
                );
            } else {
                $response = new ResponseTypeData(
                    false,
                    1,
                    [],
                    'Пользователь уже существует.'
                );
            }
        }

        echo $response->getResponse()->getJsonFormat();
    }
}
