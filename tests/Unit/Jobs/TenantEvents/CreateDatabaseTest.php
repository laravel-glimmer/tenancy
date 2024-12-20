<?php

use Glimmer\Tenancy\Jobs\TenantEvents\CreateDatabase;
use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->tenant = Tenant::factory()->make();
});

it('creates sqlite database', function () {
    $job = (new CreateDatabase($this->tenant));

    $job->handle();

    $dbPath = database_path($this->tenant->getDatabaseName().'.sqlite');
    expect(File::exists($dbPath))->toBeTrue();

    File::delete(database_path($this->tenant->getDatabaseName().'.sqlite'));
});
