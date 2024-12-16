<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\Tasks\PrefixSpatiePermissionTask;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();

    $this->anotherTenant = Tenant::factory()->create();

    Config::set('multitenancy.switch_tenant_tasks', [PrefixSpatiePermissionTask::class]);
});

test('switch prefixes spatie permission', function () {
    $registrar = app(PermissionRegistrar::class);
    $ogPrefix = $registrar->cacheKey;

    $this->tenant->makeCurrent();

    expect($registrar->cacheKey)->toBe($ogPrefix.'.'.$this->tenant->getKey());
});

test('switching tenant will switch permission prefix', function () {
    $registrar = app(PermissionRegistrar::class);
    $ogPrefix = $registrar->cacheKey;

    $this->tenant->makeCurrent();

    expect($registrar->cacheKey)->toBe($ogPrefix.'.'.$this->tenant->getKey());

    $this->anotherTenant->makeCurrent();

    expect($registrar->cacheKey)->toBe($ogPrefix.'.'.$this->anotherTenant->getKey());

    $this->tenant->forgetCurrent();

    expect($registrar->cacheKey)->toBe($ogPrefix);
});
