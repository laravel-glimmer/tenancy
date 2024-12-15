<?php

namespace Glimmer\Tenancy;

use Glimmer\Tenancy\Facades\TenancyRoutes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

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

        if (Config::get('multitenancy.use_default_routes_groups')) {
            TenancyRoutes::landlord();
            TenancyRoutes::tenant();
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/multitenancy.php', 'multitenancy');
    }
}
