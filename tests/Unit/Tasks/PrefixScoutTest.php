<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\Tasks\PrefixScoutTask;
use Glimmer\Tenancy\Tests\Stubs\Models\User;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();

    $this->anotherTenant = Tenant::factory()->create();

    $this->user = new User;

    Config::set('multitenancy.switch_tenant_tasks', [PrefixScoutTask::class]);
    Config::set('scout.prefix', 'laravel_scout.');
});

test('switching tenant will prefix laravel scout', function () {
    $ogPrefix = Config::get('scout.prefix');

    $this->tenant->makeCurrent();

    expect($this->user->searchableAs())->toBe($this->tenant->getKey().'.'.$ogPrefix.$this->user->getTable());
});

test('switching tenant will change scout prefix', function () {
    $ogPrefix = Config::get('scout.prefix');

    $this->tenant->makeCurrent();

    expect($this->user->searchableAs())->toBe($this->tenant->getKey().'.'.$ogPrefix.$this->user->getTable());

    $this->anotherTenant->makeCurrent();

    expect($this->user->searchableAs())->toBe($this->anotherTenant->getKey().'.'.$ogPrefix.$this->user->getTable());

    $this->tenant->forgetCurrent();

    expect($this->user->searchableAs())->toBe($ogPrefix.$this->user->getTable());
});
