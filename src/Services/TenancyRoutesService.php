<?php

namespace Glimmer\Tenancy\Services;

use Closure;
use Glimmer\Tenancy\Http\Middleware\EnsureNoTenantSession;
use Glimmer\Tenancy\Http\Middleware\ForbidsTenant;
use Glimmer\Tenancy\TenantFinders\PathTenantFinder;
use Illuminate\Container\Attributes\Config;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use Spatie\Multitenancy\Http\Middleware\EnsureValidTenantSession;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;

class TenancyRoutesService
{
    public function __construct(
        #[Config('multitenancy.tenant_finder')]
        protected string $defaultTenantFinder,
    ) {}

    public function landlord(
        array|Closure|string|null $routes = null,
        string $namePrefix = 'landlord.',
        string|array|null $extraMiddlewares = null
    ): RouteRegistrar {
        return Route::middleware(
            array_merge([
                'web',
                ForbidsTenant::class,
                EnsureNoTenantSession::class,
            ], (array) $extraMiddlewares)
        )->name($namePrefix)->group($routes ?? base_path('routes/landlord.php'));
    }

    public function tenant(
        array|Closure|string|null $routes = null,
        string $namePrefix = 'tenant.',
        string|array|null $extraMiddlewares = null
    ): RouteRegistrar {
        $prefix = '';

        if ($this->defaultTenantFinder == PathTenantFinder::class) {
            $prefix = '{tenant}';
        }

        return Route::middleware(
            array_merge([
                'web',
                NeedsTenant::class,
                EnsureValidTenantSession::class,
            ], (array) $extraMiddlewares)
        )->name($namePrefix)->prefix($prefix)->group($routes ?? base_path('routes/tenant.php'));
    }
}
