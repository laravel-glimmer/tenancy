<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\Tasks\SwitchDatabaseConnectionTask;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();

    $this->anotherTenant = Tenant::factory()->create();

    Config::set('multitenancy.switch_tenant_tasks', [SwitchDatabaseConnectionTask::class]);
});

it('Executes for landlord when tenant is provided as null')
    ->artisan('tenant:maybe-noop --tenant=null')
    ->assertExitCode(0)
    ->expectsOutput('Tenant ID is ');

it('Fails with non-existing tenant')
    ->artisan('tenant:maybe-noop --tenant=1000')
    ->assertExitCode(-1)
    ->expectsOutput('No tenant(s) found.');

it('Asks for tenants or landlord when tenant is not provided and executes for landlord')
    ->artisan('tenant:maybe-noop')
    ->expectsChoice('Do you want to run this command for `landlord` or all `tenants`?', 'landlord',
        ['landlord', 'tenants'])
    ->assertExitCode(0)
    ->expectsOutput('Tenant ID is ');

it('Asks for tenants or landlord when tenant is not provided and executes for tenants', function () {
    $this->artisan('tenant:maybe-noop')
        ->expectsChoice('Do you want to run this command for `landlord` or all `tenants`?', 'tenants',
            ['landlord', 'tenants'])
        ->assertExitCode(0)
        ->expectsOutput('Tenant ID is '.$this->tenant->id)
        ->expectsOutput('Tenant ID is '.$this->anotherTenant->id);
});
