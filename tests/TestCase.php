<?php

namespace Glimmer\Tenancy\Tests;

use Glimmer\Tenancy\Facades\TenancyRoutes;
use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\TenancyServiceProvider;
use Glimmer\Tenancy\TenantFinders\PathTenantFinder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Multitenancy\MultitenancyServiceProvider;

#[WithMigration]
#[WithMigration('queue')]
abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Glimmer\\Tenancy\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        TenancyRoutes::landlord(__DIR__.'/../routes/landlord.php');
        TenancyRoutes::tenant(__DIR__.'/../routes/tenant.php');
    }

    protected function getPackageProviders($app): array
    {
        return [
            MultitenancyServiceProvider::class,
            TenancyServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        Config::set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        Config::set('multitenancy.tenant_finder', PathTenantFinder::class);
        Config::set('multitenancy.tenant_model', Tenant::class);
        Config::set('multitenancy.use_default_routes_groups', false);
    }
}
