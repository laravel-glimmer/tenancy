<?php

namespace Glimmer\Tenancy\Services;

use Glimmer\Tenancy\Http\Middleware\EnsureNoTenantSession;
use Glimmer\Tenancy\Http\Middleware\ForbidsTenant;
use Glimmer\Tenancy\Routing\TenancyRouteRegistrar;
use Glimmer\Tenancy\TenantFinders\PathTenantFinder;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;

class TenancyRouteService
{
    public function landlord(): RouteRegistrar
    {
        return (new TenancyRouteRegistrar(Route::getFacadeRoot()))
            ->defaultMiddlewares([
                'web',
                ForbidsTenant::class,
                EnsureNoTenantSession::class,
            ]);
    }

    public function tenant(): RouteRegistrar
    {
        if (Config::get('multitenancy.tenant_finder') === PathTenantFinder::class) {
            $prefix = '{tenant}';
        }

        return (new TenancyRouteRegistrar(Route::getFacadeRoot()))
            ->defaultMiddlewares([
                'web',
                NeedsTenant::class,
                EnsureValidTenantSession::class,
            ])
            ->prefix($prefix ?? '');
    }
}
