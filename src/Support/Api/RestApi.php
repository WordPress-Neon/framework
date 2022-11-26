<?php

namespace WPN\Support\Api;

use Closure;
use ReflectionClass;

class RestApi
{
    public static function route(string $route, string $method, string|Closure $callback, string|Closure $middleware)
    {
        $callback = is_callable($callback) ? $callback : function () use ($callback) {
            (new $callback())();
        };

        $middleware_callback = is_callable($middleware) ? $middleware : function () use ($middleware) {
            $reflection = new ReflectionClass($middleware);

            return $reflection->hasMethod('authenticate') ? (new $middleware())->authenticate() : (new $middleware())();
        };

        register_rest_route('theme/v1', $route, array(
            'methods'             => $method,
            'callback'            => $callback,
            'permission_callback' => $middleware_callback,
        ));
    }
}