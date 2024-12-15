<?php

namespace Glimmer\Tenancy\Http\Middleware;

use Closure;
use Glimmer\Tenancy\Exceptions\TenantIsForbidden;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Contracts\IsTenant;
use Symfony\Component\HttpFoundation\Response;

class ForbidsTenant
{
    /**
     * @throws TenantIsForbidden
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app(IsTenant::class)::checkCurrent()) {
            throw TenantIsForbidden::make();
        }

        return $next($request);
    }
}
