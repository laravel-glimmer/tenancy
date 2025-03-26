<?php

use Glimmer\Tenancy\Facades\TenancyRoute;
use Glimmer\Tenancy\Http\Middleware\EnsureNoTenantSession;
use Glimmer\Tenancy\Http\Middleware\ForbidsTenant;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->route = TenancyRoute::landlord()->middleware('api')->get('landlord', fn () => 'ok');
});

it('adds defaults middlewares', function () {
    expect(Route::gatherRouteMiddleware($this->route))->toMatchArray([
        'web',
        ForbidsTenant::class,
        EnsureNoTenantSession::class,
    ]);
});

it('merges new middlewares with defaults', function () {
    expect(Route::gatherRouteMiddleware($this->route))
        ->toMatchArray([
            'web',
            ForbidsTenant::class,
            EnsureNoTenantSession::class,
            'api',
        ]);
});
