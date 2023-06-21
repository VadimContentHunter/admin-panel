<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\validations\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IValidation
{
    public function hasValidating(): bool;

    /**
     * @return mixed[]
     */
    public function getResult(): array;
}
