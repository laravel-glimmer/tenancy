<?php

namespace Glimmer\Tenancy\Facades;

use Closure;
use Glimmer\Tenancy\Services\LandlordTenantExceptionService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Closure redirect(string $route)
 */
class LandlordTenantException extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LandlordTenantExceptionService::class;
    }
}
