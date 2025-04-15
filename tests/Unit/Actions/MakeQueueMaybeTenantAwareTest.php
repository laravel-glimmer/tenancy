<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\Tests\Stubs\Jobs\TenantAwareJob;
use Spatie\Multitenancy\Exceptions\CurrentTenantCouldNotBeDeterminedInTenantAwareJob;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
});

it("doesn't throw an exception on TenantAware job if tenant is provided", function () {
    $this->tenant->makeCurrent();
    expect(fn () => TenantAwareJob::dispatch())
        ->not->toThrow(CurrentTenantCouldNotBeDeterminedInTenantAwareJob::class);
});

it('does throw an exception on TenantAware job when tenant is not provided', function () {
    expect(fn () => TenantAwareJob::dispatch())
        ->toThrow(CurrentTenantCouldNotBeDeterminedInTenantAwareJob::class);
});

it("does throw an exception when Tenant doesn't exists", function () {
    $this->tenant->makeCurrent();
    $this->tenant->delete();

    expect(fn () => TenantAwareJob::dispatch())
        ->toThrow(CurrentTenantCouldNotBeDeterminedInTenantAwareJob::class);
});
