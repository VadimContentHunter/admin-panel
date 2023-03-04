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

    public function addRoute(string $url, string $class_name, string $method_name): Routing
    {
        $this->routes[] = new Route($url, $class_name, $method_name);
        return $this;
    }

    /**
     * @param mixed[] $parameters
     */
    public function start(string $url, array $parameters = []): void
    {
        foreach ($this->routes as $key => $route) {
            if (strcasecmp($url, $route->getUrl()) === 0) {
                $class_name = $route->getClassName();
                $method_name = $route->getMethodName();
                $obj = new $class_name();
                $obj->$method_name($parameters);
                break;
            }
        }
    }
}
