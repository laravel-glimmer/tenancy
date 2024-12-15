<?php

namespace Glimmer\Tenancy\Facades;

use Closure;
use Glimmer\Tenancy\Services\TenancyRoutesService;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Facade;

/**
 * @method static RouteRegistrar landlord(array|Closure|string|null $routes = null)
 * @method static RouteRegistrar tenant(array|Closure|string|null $routes = null)
 */
class TenancyRoutes extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TenancyRoutesService::class;
    }
}
