<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\routing;

use vadimcontenthunter\AdminPanel\routing\interfaces\IRoute;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Route implements IRoute
{
    /**
     * @param mixed[] $parameters
     */
    public function __construct(
        protected string $pattern,
        protected string $class_name,
        protected ?string $method_name = null,
        protected array $parameters = []
    ) {
    }
    public function setPattern(string $pattern): IRoute
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function setClassName(string $class_name): IRoute
    {
        $this->class_name = $class_name;
        return $this;
    }

    public function setMethodName(string $method_name): IRoute
    {
        $this->method_name = $method_name;
        return $this;
    }

    /**
     * @param mixed[] $parameters
     */
    public function setParameters(array $parameters): IRoute
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getClassName(): string
    {
        return $this->class_name;
    }

    /**
     * @return mixed[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getMethodName(): ?string
    {
        return $this->method_name;
    }
}
