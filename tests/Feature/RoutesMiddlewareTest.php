<?php

use Glimmer\Tenancy\Models\Tenant;
use Glimmer\Tenancy\TenantFinders\DomainTenantFinder;
use Glimmer\Tenancy\Tests\Stubs\Models\User;

describe('ForbidsTenant middleware', function () {
    it('allows access to tenant routes when a tenant is current', function () {
        $tenant = Tenant::factory()->create()->makeCurrent();

        $this->get(route('tenant.tenant', $tenant->getKey()))->assertOk();
    });

    it('allows access to landlord routes when no tenant is current', function () {
        $this->get(route('landlord.landlord'))->assertOk();
    });

    it('denies access to tenant routes when no tenant is current', function () {
        Tenant::factory()->create();

        $this->get(route('tenant.tenant', 1))->assertInternalServerError();
    });

    it('denies access to landlord routes when a tenant is current', function () {
        Tenant::factory()->create()->makeCurrent();

        $this->get(route('landlord.landlord'))->assertInternalServerError();
    });
});

describe('EnsureNoTenantSession middleware', function () {
    it('denies access to landlord route when logged in with tenant user', function () {
        $this->withSession(['ensure_valid_tenant_session_tenant_id' => 'a'])
            ->actingAs(User::create([
                'name' => 'a', 'email' => 'a', 'password' => 'a',
            ]))
            ->get(route('landlord.landlord'))
            ->assertUnauthorized();
    });

    it('allows access to landlord route when not logged in with tenant user and clears session', function () {
        $this->withSession(['ensure_valid_tenant_session_tenant_id' => 'a'])
            ->get(route('landlord.landlord'))
            ->assertRedirect(route('landlord.landlord'))
            ->assertSessionMissing('ensure_valid_tenant_session_tenant_id');
    });

    it('denies access to landlord route with a tenant session set and different TenantFinder', function () {
        Config::set('multitenancy.tenant_finder', DomainTenantFinder::class);
        $this->withSession(['ensure_valid_tenant_session_tenant_id' => 'a'])
            ->get(route('landlord.landlord'))
            ->assertUnauthorized();
    });
});
