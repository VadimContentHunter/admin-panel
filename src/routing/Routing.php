<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\routing;

use vadimcontenthunter\AdminPanel\routing\Route;
use vadimcontenthunter\AdminPanel\routing\interfaces\IRoute;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Routing
{
    /**
     * @var IRoute[]
     */
    protected array $routes = [];

    /**
     * @param mixed[] $parameters
     */
    public function addRoute(string $pattern, string $class_name, string $method_name, array $parameters = []): Routing
    {
        $this->routes[] = new Route($pattern, $class_name, $method_name, $parameters);
        return $this;
    }

    /**
     * @param mixed[] $parameters
     */
    public function start(string $current_url, array $parameters = []): void
    {
        foreach ($this->routes as $key => $route) {
            if (preg_match($route->getPattern(), $current_url, $matches)) {
                unset($matches[0]);
                $parameters += $matches;
                $parameters += $route->getParameters();

                $class_name = $route->getClassName();
                $method_name = $route->getMethodName();
                $obj = new $class_name();
                $obj->$method_name($parameters);

                return;
            }
        }

        echo 'Страница не найдена!';
    }

    /**
     * @param mixed[] $parameters
     */
    public function searchByPattern(string $pattern): IRoute|null
    {
        foreach ($this->routes as $key => $route) {
            if (preg_match('~' . $pattern . '~u', $route->getPattern())) {
                return $route;
            }
        }

        return null;
    }
}
