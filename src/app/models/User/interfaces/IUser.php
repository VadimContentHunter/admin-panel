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

    public function setPassword(string $password): IUser;

    public function setPasswordHash(): IUser;

    public function getId(): int;

    public function getName(): ?string;

    public function getEmail(): ?string;

    public function getPassword(): ?string;

    public function validateByNameAndPassword(): bool;

    public static function selectByEmailAndPassword(string $email, string $password): ?IUser;
}
