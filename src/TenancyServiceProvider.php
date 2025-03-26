<?php

namespace Glimmer\Tenancy;

use Glimmer\Tenancy\Commands\TenantsArtisanCommand;
use Glimmer\Tenancy\Facades\TenancyRoute;
use Glimmer\Tenancy\Services\TenancyRouteService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Spatie\Multitenancy\Commands\TenantsArtisanCommand as SpatieTenantsArtisanCommand;

class TenancyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/multitenancy.php' => config_path('multitenancy.php'),
            __DIR__.'/../routes/landlord.php' => base_path('routes/landlord.php'),
            __DIR__.'/../routes/tenant.php' => base_path('routes/tenant.php'),
        ]);

        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                TenantsArtisanCommand::class,
            ]);
        }

        $this->autoRegisterTenancyRoutes();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/multitenancy.php', 'multitenancy');

        if ($this->app->runningInConsole()) {
            $this->app->extend(SpatieTenantsArtisanCommand::class, function () {
                return new TenantsArtisanCommand;
            });
        }

        $this->app->singleton(TenancyRouteService::class, function () {
            return new TenancyRouteService;
        });
    }

    public function autoRegisterTenancyRoutes(): void
    {
        if (Config::get('multitenancy.use_default_routes_groups')) {
            $routesPrefix = Config::get('multitenancy.routes_prefix', '');

            TenancyRoute::landlord()->group(base_path($routesPrefix.'routes/landlord.php'));
            TenancyRoute::tenant()->group(base_path($routesPrefix.'routes/tenant.php'));
        }
    }

    // @codeCoverageIgnoreStart
    public function provides(): array
    {
        return [SpatieTenantsArtisanCommand::class];
    }
    // @codeCoverageIgnoreEnd
}
