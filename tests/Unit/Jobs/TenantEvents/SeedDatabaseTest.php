<?php

use Glimmer\Tenancy\Jobs\TenantEvents\CreateDatabase;
use Glimmer\Tenancy\Jobs\TenantEvents\MigrateDatabase;
use Glimmer\Tenancy\Jobs\TenantEvents\SeedDatabase;
use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Contracts\Container\BindingResolutionException;

beforeEach(function () {
    $this->tenant = Tenant::factory()->make();
});

it('seeds tenant database', function () {
    (new CreateDatabase($this->tenant))->handle();
    (new MigrateDatabase($this->tenant))->handle();
    $job = (new SeedDatabase($this->tenant));

    expect(fn () => $job->handle())->toThrow(BindingResolutionException::class);

    File::delete(database_path($this->tenant->getDatabaseName().'.sqlite'));
});
