<?php

namespace Glimmer\Tenancy\TenantFinders;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class PathTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?IsTenant
    {
        $tenantId = $request->segment(1);

        return app(IsTenant::class)::whereKey($tenantId)->first();
    }
}
