<?php

namespace Glimmer\Tenancy\Http\Middleware;

use Closure;
use Glimmer\Tenancy\TenantFinders\PathTenantFinder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Symfony\Component\HttpFoundation\Response;

class EnsureNoTenantSession
{
    use UsesMultitenancyConfig;

    public function handle(Request $request, Closure $next)
    {
        $sessionKey = 'ensure_valid_tenant_session_tenant_id';

        if ($request->session()->has($sessionKey)) {
            if ($this->isPathTenantFinder()) {
                if (Auth::check()) {
                    $this->handleInvalidLandlordAccess();
                }

                $request->session()->flush();

                return redirect()->refresh();
            }

            $this->handleInvalidLandlordAccess();
        }

        return $next($request);
    }

    protected function handleInvalidLandlordAccess(): void
    {
        abort(Response::HTTP_UNAUTHORIZED);
    }

    protected function isPathTenantFinder(): bool
    {
        return Config::get('multitenancy.tenant_finder') === PathTenantFinder::class;
    }
}
