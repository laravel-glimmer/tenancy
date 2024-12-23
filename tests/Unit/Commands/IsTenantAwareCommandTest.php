<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\Tasks\SwitchDatabaseConnectionTask;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();

    $this->anotherTenant = Tenant::factory()->create();

    Config::set('multitenancy.switch_tenant_tasks', [SwitchDatabaseConnectionTask::class]);
});

it('fails with a non-existing tenant')
    ->artisan('tenant:noop --tenant=1000')
    ->assertExitCode(-1)
    ->expectsOutput('No tenant(s) found.');

it('works with no tenant parameters', function () {
    $this
        ->artisan('tenant:noop')
        ->assertExitCode(0)
        ->expectsOutput('Tenant ID is '.$this->tenant->id)
        ->expectsOutput('Tenant ID is '.$this->anotherTenant->id);
});
