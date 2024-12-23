<?php

use Illuminate\Support\Facades\Route;
use Spatie\Multitenancy\Contracts\IsTenant;

Route::get('/tenant', function () {
    if (! app(IsTenant::class)::checkCurrent()) {
        abort(500, 'Current tenant should not be null');
    }

    return 'Multi-tenant application. Tenant only route. Current tenant: '.app(IsTenant::class)::current()->getKey();
})->name('tenant');
