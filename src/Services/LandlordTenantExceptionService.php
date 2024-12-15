<?php

namespace Glimmer\Tenancy\Services;

use Closure;
use Glimmer\Tenancy\Http\Middleware\ForbidsTenant;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;

class LandlordTenantExceptionService
{
    public function redirect(string $route): Closure
    {
        return function (NoCurrentTenant|ForbidsTenant $exception, Request $request) use ($route) {
            return redirect()->route($route);
        };
    }
}
