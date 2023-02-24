<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\model\User\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IUser
{
    public function setId(int $id): IUser;

    public function setName(string $user_name): IUser;

    public function setEmail(string $email): IUser;

    public function setPasswordHash(string $password): IUser;

    public function getId(): int;

    public function getName(): ?string;

    public function getEmail(): ?string;

    public function getPasswordHash(): ?string;

    public function validateByNameAndPassword(): bool;

    public static function selectByEmailAndPassword(string $email, string $password): ?IUser;
}
