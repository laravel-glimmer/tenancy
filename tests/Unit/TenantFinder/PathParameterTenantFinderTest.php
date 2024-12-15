<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\TenantFinders\PathTenantFinder;

beforeEach(function () {
    $this->tenantFinder = new PathTenantFinder;
});

it('can find tenant by first path parameter', function () {
    $tenant = Tenant::factory()->create();

    $request = Request::create('https://example.com/'.$tenant->id.'/tenant');

    expect($tenant->getKey())->toEqual($this->tenantFinder->findForRequest($request)?->getKey());
});

it('will return null if there are no tenants', function () {
    $request = Request::create('https://example.com/1/tenant');

    expect($this->tenantFinder->findForRequest($request))->toBeNull();
});

it('will return null if no tenant can be found for the current path parameter', function () {
    Tenant::factory()->create();

    $request = Request::create('https://example.com/2/tenant');

    expect($this->tenantFinder->findForRequest($request))->toBeNull();
});
