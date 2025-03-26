<?php

namespace Glimmer\Tenancy\Facades;

use Glimmer\Tenancy\Services\TenancyRouteService;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Facade;

/**
 * @method static RouteRegistrar landlord()
 * @method static RouteRegistrar tenant()
 */
class TenancyRoute extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TenancyRouteService::class;
    }
}
