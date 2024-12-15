<?php

use Spatie\Multitenancy\Contracts\IsTenant;

Route::get('/landlord', function () {
    return 'Multi-tenant application. Landlord only route. Current tenant (should be null): '
        .var_export(app(IsTenant::class)::current()?->getKey(), true);
})->name('landlord');
