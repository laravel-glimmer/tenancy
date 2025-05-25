<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\Tasks\SwitchDatabaseConnectionTask;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->defaultConnection = DB::getDefaultConnection();
    $this->tenant = Tenant::factory()->create();

    Config::set('queue.connections.test', [
        'driver' => 'database',
        'connection' => null,
    ]);

    Config::set('multitenancy.switch_tenant_tasks', [SwitchDatabaseConnectionTask::class]);
});

it("set's queue connections with database driver and null connection to landlord connection", function () {
    $this->tenant->makeCurrent();

    expect(Config::get('queue.connections.database.connection'))->toBe($this->defaultConnection)
        ->and(Config::get('queue.connections.test.connection'))->toBe($this->defaultConnection);

    Tenant::forgetCurrent();
});

it("set's back to null queue connections with database driver and landlord connection", function () {
    $this->tenant->makeCurrent();

    Tenant::forgetCurrent();

    expect(Config::get('queue.connections.database.connection'))->toBeNull()
        ->and(Config::get('queue.connections.test.connection'))->toBeNull();
});
