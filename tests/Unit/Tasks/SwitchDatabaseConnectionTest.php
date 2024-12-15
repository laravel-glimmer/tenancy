<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\Tasks\SwitchDatabaseConnectionTask;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();

    $this->anotherTenant = Tenant::factory()->create([
        'database_connection' => 'mysql',
        'connection_config' => [
            'driver' => 'mysql',
            'database' => 'another',
        ],
    ]);

    Config::set('multitenancy.switch_tenant_tasks', [SwitchDatabaseConnectionTask::class]);
});

test('creating tenant connection will merge configs', function () {
    $this->anotherTenant->makeCurrent();

    expect(Config::get("database.connections.{$this->anotherTenant->getKey()}"))->toBe(array_merge(
        Config::get("database.connections.{$this->anotherTenant->database_connection}"),
        $this->anotherTenant->connection_config?->toArray(),
    ));

    $this->tenant->forgetCurrent();
});

test('switching tenant will switch default connection', function () {
    $this->tenant->makeCurrent();

    expect(DB::getDefaultConnection())->toBe($this->tenant->getKey());

    $this->anotherTenant->makeCurrent();

    expect(DB::getDefaultConnection())->toBe($this->anotherTenant->getKey());

    Tenant::forgetCurrent();

    expect(DB::getDefaultConnection())->toBe(Config::get('database.default'));
});
