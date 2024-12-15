<?php

use Spatie\Multitenancy\Contracts\IsTenant;

Route::get('/tenant', function () {
    return 'Multi-tenant application. Tenant only route. Current tenant: '
        .app(IsTenant::class)->getKey();
})->name('tenant');
