<?php

namespace Glimmer\Tenancy\Jobs\Concerns;

use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Jobs\NotTenantAware;

abstract class TenantEventQueue implements NotTenantAware, ShouldQueue
{
    use Queueable;

    public function __construct(
        public IsTenant|Tenant $tenant
    ) {}

    abstract public function handle(): void;
}
