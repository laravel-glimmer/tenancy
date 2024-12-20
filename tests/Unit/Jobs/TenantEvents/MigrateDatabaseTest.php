<?php

use Glimmer\Tenancy\Jobs\TenantEvents\CreateDatabase;
use Glimmer\Tenancy\Jobs\TenantEvents\MigrateDatabase;
use Illuminate\Support\Facades\File;
use Spatie\Multitenancy\Models\Tenant;

use function Pest\Laravel\assertDatabaseEmpty;

beforeEach(function () {
    $this->tenant = Tenant::factory()->make();
});

it('migrates tenant database', function () {
    (new CreateDatabase($this->tenant))->handle();
    $job = (new MigrateDatabase($this->tenant));

    $job->handle();

    assertDatabaseEmpty('users');

    File::delete(database_path($this->tenant->getDatabaseName().'.sqlite'));
});
