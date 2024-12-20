<?php

namespace Glimmer\Tenancy\Jobs\TenantEvents;

use Glimmer\Tenancy\Jobs\Concerns\TenantEventQueue;
use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Spatie\Multitenancy\Contracts\IsTenant;

class MigrateDatabase extends TenantEventQueue
{
    public function __construct(public IsTenant|Tenant $tenant, public bool $fresh = false)
    {
        parent::__construct($tenant);
    }

    public function handle(): void
    {
        $this->tenant->execute(function () {
            Artisan::call($this->fresh ? 'migrate:fresh' : 'migrate', [
                '--force' => true,
                '--path' => 'database/migrations/tenant',
            ]);
        });
    }
}
