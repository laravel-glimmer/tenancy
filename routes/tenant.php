<?php

use Illuminate\Support\Facades\Route;
use Spatie\Multitenancy\Contracts\IsTenant;

/**
 * All routes defined here are functional and prefixed with `tenant.` name and may be prefixed with `{tenant}`
 * uri if `TenantFinder` is `PathTenantFinder`; unless auto-registration is disabled.
 */
Route::get('/tenant', function () {
    if (! app(IsTenant::class)::checkCurrent()) {
        abort(500, 'Current tenant should not be null');
    }

    return 'Multi-tenant application. Tenant only route. Current tenant: '.app(IsTenant::class)::current()->getKey();
})->name('tenant');
