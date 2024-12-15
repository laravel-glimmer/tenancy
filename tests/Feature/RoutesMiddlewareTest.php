<?php

use Glimmer\Tenancy\Models\Tenant;

it('can access tenant routes with a tenant', function () {
    $tenant = Tenant::factory()->create()->makeCurrent();

    $route = route('tenant.tenant', $tenant->getKey());

    $this->get($route)->assertOk();
});

it('can access landlord routes without a tenant', function () {
    $route = route('landlord.landlord');

    $this->get($route)->assertOk();
});

it("can't access tenant routes without a tenant", function () {
    Tenant::factory()->create();

    $route = route('tenant.tenant', 1);

    $this->get($route)->assertInternalServerError();
});

it("can't access landlord routes with a tenant", function () {
    Tenant::factory()->create()->makeCurrent();

    $route = route('landlord.landlord');

    $this->get($route)->assertInternalServerError();
});
