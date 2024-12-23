<?php

use Illuminate\Support\Facades\Route;
use Spatie\Multitenancy\Contracts\IsTenant;

Route::get('/landlord', function () {
    if (app(IsTenant::class)::checkCurrent()) {
        abort(500, 'Current tenant should be null');
    }

    return 'Multi-tenant application. Landlord only route. Current tenant (should be null): '
        .var_export(app(IsTenant::class)::current()?->getKey(), true);
})->name('landlord');
