<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\modules\BlockManagement\models\Block\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IBlock
{
    public function setName(string $name): IBlock;

    public function getName(): string;

    public function setPathBlockView(string $path): IBlock;

    public function getPathBlockView(): string;

    public function setDescription(string $description): IBlock;

    public function getDescription(): string;

    /**
     * @param array<string> $parameters
     */
    public function setParameters(array $parameters): IBlock;

    /**
     * @return array<string> $parameters
     */
    public function getParameters(): array;

    public function getParameter(string $key): ?string;
}
