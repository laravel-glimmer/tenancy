<?php

namespace Glimmer\Tenancy\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class SubDomainTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?IsTenant
    {
        $host = $request->getHost();

        return app(IsTenant::class)::whereJsonContains('hosts', explode('.', $host)[0])->first();
    }
}
