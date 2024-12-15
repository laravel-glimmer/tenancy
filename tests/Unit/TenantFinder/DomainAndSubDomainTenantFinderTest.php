<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\TenantFinders\DomainAndSubDomainTenantFinder;
use Illuminate\Http\Request;

beforeEach(function () {
    $this->tenantFinder = new DomainAndSubDomainTenantFinder;
});

it('can find tenant by domain', function () {
    $tenant = Tenant::factory()->create(['hosts' => ['example.com', 'tenant']]);

    $request = Request::create('https://example.com');

    expect($tenant->getKey())->toEqual($this->tenantFinder->findForRequest($request)?->getKey());
});

it('can find tenant by subdomain', function () {
    $tenant = Tenant::factory()->create(['hosts' => ['example.com', 'tenant']]);

    $request = Request::create('https://tenant.domain.com');

    expect($tenant->getKey())->toEqual($this->tenantFinder->findForRequest($request)?->getKey());
});

it('will return null if there are no tenants', function () {
    $request = Request::create('https://example.com');

    expect($this->tenantFinder->findForRequest($request))->toBeNull();
});

it('will return null if no tenant can be found for the current domain or subdomain', function () {
    $tenant = Tenant::factory()->create(['hosts' => ['example.com', 'tenant']]);

    $request = Request::create('https://domain.com');

    expect($this->tenantFinder->findForRequest($request))->toBeNull();
});
