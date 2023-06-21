<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\routing\interfaces;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface IRoute
{
    public function setPattern(string $url): IRoute;

    public function setClassName(string $class_name): IRoute;

    public function setMethodName(string $method_name): IRoute;

    /**
     * @param mixed[] $parameters
     */
    public function setParameters(array $parameters): IRoute;

    public function getPattern(): string;

    public function getClassName(): string;

    /**
     * @return mixed[]
     */
    public function getParameters(): array;

    public function getMethodName(): ?string;
}
