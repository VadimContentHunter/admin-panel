<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\routing;

use vadimcontenthunter\JsonRpc\JsonRpcBatch;
use vadimcontenthunter\JsonRpc\JsonRpcError;
use vadimcontenthunter\JsonRpc\JsonRpcResponse;
use vadimcontenthunter\AdminPanel\routing\Route;
use vadimcontenthunter\JsonRpc\interfaces\IJsonRpcRequest;
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
    public function addRoute(string $pattern, string $class_name, ?string $method_name = null, array $parameters = []): Routing
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

                if (!empty($parameters['module_name']) && !empty($parameters['execution_method'])) {
                    $this->handlerRestModule($route, $parameters);
                    return;
                }

                $request = file_get_contents('php://input');
                if (!is_string($request) || $request === '') {
                    $this->handlerPage($route, $parameters);
                } else {
                    $this->handlerJsonRpcRequest($request, $route, $parameters);
                }

                return;
            }
        }

        echo 'Страница не найдена!';
    }

    /**
     * @param mixed[] $parameters
     */
    protected function handlerRestModule(IRoute $route, array $parameters = []): void
    {
        $parameters += $route->getParameters();

        $class_name = $route->getClassName();
        $method_name = $parameters['execution_method'] ?? null;
        $obj = new $class_name();
        $obj->$method_name($parameters);
    }

    /**
     * @param mixed[] $parameters
     */
    protected function handlerJsonRpcRequest(string $request, IRoute $route, array $parameters = []): void
    {
        $class_name = $route->getClassName();
        $parameters += $route->getParameters();

        $response = new JsonRpcResponse(error: new JsonRpcError(1, 'Неизвестная ошибка'));
        $jsonRpcBatch = new JsonRpcBatch();
        $objRequest = $jsonRpcBatch->createBatchRequestFromJson($request)->getBatch()[0] ?? null;
        if ($objRequest instanceof IJsonRpcRequest) {
            $method_name = $objRequest->getMethod();
            if (method_exists($class_name, $method_name)) {
                $parameters += $objRequest->getParams();
                $parameters['request_id'] = $objRequest->getId();

                $obj = new $class_name();
                $obj->$method_name($parameters);
            }
        } else {
            echo $response->getJsonRequest();
        }
    }

    /**
     * @param mixed[] $parameters
     */
    protected function handlerPage(IRoute $route, array $parameters = []): void
    {
        $parameters += $route->getParameters();

        $class_name = $route->getClassName();
        $method_name = $route->getMethodName();
        $obj = new $class_name();
        $obj->$method_name($parameters);
    }

    public function searchByPattern(string $url): IRoute|null
    {
        foreach ($this->routes as $key => $route) {
            if (preg_match($route->getPattern(), $url)) {
                return $route;
            }
        }

        return null;
    }
}
