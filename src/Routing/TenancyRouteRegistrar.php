<?php

namespace Glimmer\Tenancy\Routing;

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Arr;

class TenancyRouteRegistrar extends RouteRegistrar
{
    protected array $defaultMiddlewares = [];

    public function defaultMiddlewares(array $middlewares): RouteRegistrar
    {
        $this->defaultMiddlewares = $middlewares;

        return $this->attribute('middleware', $middlewares);
    }

    public function __call($method, $parameters)
    {
        if ($method === 'middleware') {
            $parameters = array_merge($this->defaultMiddlewares, Arr::wrap($parameters));
        }

        return parent::__call($method, $parameters);
    }
}
