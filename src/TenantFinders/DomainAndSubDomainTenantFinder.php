<?php

namespace Glimmer\Tenancy\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainAndSubDomainTenantFinder extends TenantFinder
{
    protected DomainTenantFinder $domainTenantFinder;

    protected SubDomainTenantFinder $subDomainTenantFinder;

    public function __construct()
    {
        $this->domainTenantFinder = new DomainTenantFinder;
        $this->subDomainTenantFinder = new SubDomainTenantFinder;
    }

    public function findForRequest(Request $request): ?IsTenant
    {
        return $this->domainTenantFinder->findForRequest($request) ?? $this->subDomainTenantFinder->findForRequest($request);
    }
}
