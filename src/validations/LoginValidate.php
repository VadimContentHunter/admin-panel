<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\validations;

use vadimcontenthunter\AdminPanel\models\User\User;
use vadimcontenthunter\AdminPanel\models\User\interfaces\IUser;
use vadimcontenthunter\AdminPanel\exceptions\AdminPanelException;
use vadimcontenthunter\AdminPanel\validations\interfaces\IValidation;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class LoginValidate implements IValidation
{
    protected bool $hasValidated = true;

    /**
     * @var mixed[]
     */
    protected array $result = [];

    /**
     * @return bool Возвращает true, если Валидация прошла успешно.
     */
    public function hasValidating(): bool
    {
        return $this->hasValidated;
    }

    /**
     * @return mixed[]
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @throws AdminPanelException
     */
    protected function saveOutput(?string $output_key = null, ?string $output_message = null): void
    {
        if ($output_key === null && $output_message === null) {
            return;
        }

        if ($output_key === null || $output_message === null) {
            throw new AdminPanelException("Error, one of the parameters is null.");
        }

        $this->result += [$output_key => $output_message];
    }

    /**
     * @param array<string, string> $output
     *
     * @throws AdminPanelException
     */
    protected function saveOutputArray(array $output = []): void
    {
        $this->result += $output;
    }

    /**
     * @return bool Возвращает true в случае, если имя пользователя пустое иначе false
     */
    public function isNameEmpty(string $user_name, ?string $output_key = null, ?string $output_message = null): bool
    {
        if ($user_name === '') {
            $this->saveOutput($output_key, $output_message);
            $this->hasValidated = false;
            return true;
        }

        return false;
    }

    /**
     * @return bool Возвращает true в случае, если имя email пустой иначе false
     */
    public function isEmailEmpty(string $user_email, ?string $output_key = null, ?string $output_message = null): bool
    {
        if ($user_email === '') {
            $this->saveOutput($output_key, $output_message);
            $this->hasValidated = false;
            return true;
        }

        return false;
    }

    /**
     * @return bool Возвращает true в случае, если пароль пустой иначе false
     */
    public function isPasswordEmpty(string $user_password, ?string $output_key = null, ?string $output_message = null): bool
    {
        if ($user_password === '') {
            $this->saveOutput($output_key, $output_message);
            $this->hasValidated = false;
            return true;
        }

        return false;
    }

    /**
     * @return bool Возвращает true в случае, если почта пользователя существует иначе false
     */
    public function isExistingMail(IUser $objUser, string $user_email, ?string $output_key = null, ?string $output_message = null): bool
    {
        if ($objUser::selectByEmail($user_email) !== null) {
            $this->saveOutput($output_key, $output_message);
            $this->hasValidated = false;
            return true;
        }

        return false;
    }

    /**
     * @return bool Возвращает true в случае, если Пароли не совпадают совпадают иначе false
     */
    public function checkPasswordMatchWithConfirmPassword(
        string $user_password,
        string $user_confirm_password,
        ?string $output_key = null,
        ?string $output_message = null
    ): bool {
        if (strcmp($user_password, $user_confirm_password) !== 0) {
            $this->saveOutput($output_key, $output_message);
            $this->hasValidated = false;
            return true;
        }
        return false;
    }
}
