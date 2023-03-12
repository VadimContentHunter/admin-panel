<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\models\User\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IUser
{
    public function setName(string $user_name): IUser;

    public function setEmail(string $email): IUser;

    public function setPasswordHash(string $password): IUser;

    public function getName(): string;

    public function getEmail(): string;

    public function getPasswordHash(): string;

    public function validateByEmailAndPassword(): bool;

    public function setSessionFromObject(): void;

    public static function deleteSessionData(): void;

    public static function createObjectFromSession(?string $name = null): IUser;

    public static function selectByEmailAndPassword(string $email, string $password): ?IUser;

    public static function selectByEmail(string $email): ?IUser;
}
