<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes = [];

    /* Общий метод регистрации маршрутов */
    private function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[$requestMethod][$this->formatRoute($route)] = $action;
        return $this;
    }

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register('post', $route, $action);
    }

    public function put(string $route, callable|array $action): self
    {
        return $this->register('put', $route, $action);
    }

    public function delete(string $route, callable|array $action): self
    {
        return $this->register('delete', $route, $action);
    }

    private function formatRoute(string $route): string
    {
        return '/' . trim($route, '/');
        //return rtrim($route, '/');
    }

    private function matchRoute(string $route, string $requestUri): ?array
    {
        $routePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $route);
        $routePattern = "#^" . $routePattern . "$#";

        if (preg_match($routePattern, $requestUri, $matches)) {
            array_shift($matches);
            return $matches;
        }

        return null;
    }

    public function resolve(string $requestUri, string $requestMethod)
    {
        $requestUri = $this->formatRoute(explode('?', $requestUri)[0]);
        $routes = $this->routes[strtolower($requestMethod)] ?? [];

        foreach ($routes as $route => $action) {
            if (($params = $this->matchRoute($route, $requestUri)) !== null) {

                if (is_callable($action)) {
                    return call_user_func_array($action, $params);
                }

                [$class, $method] = $action;

                if (class_exists($class)) {

                    $class = new $class();

                    if (method_exists($class, $method)) {
                        return call_user_func_array([$class, $method], $params);
                    }

                }

                throw new RouteNotFoundException();
            }
        }

        throw new RouteNotFoundException();
    }

}
