<?php

namespace Glimmer\Tenancy\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Symfony\Component\HttpFoundation\Response;

class EnsureNoTenantSession
{
    use UsesMultitenancyConfig;

    public function handle($request, Closure $next)
    {
        $sessionKey = 'ensure_valid_tenant_session_tenant_id';

        if (
            $request->session()->has($sessionKey) &&
            $request->session()->get($sessionKey) === app($this->currentTenantContainerKey())->getKey()
        ) {
            $this->handleInvalidLandlordAccess($request);
        }

        return $next($request);
    }

    protected function handleInvalidLandlordAccess($request)
    {
        abort(Response::HTTP_UNAUTHORIZED);
    }
}
