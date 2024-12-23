<?php

namespace Glimmer\Tenancy\Tests\Stubs\Commands;

use Glimmer\Tenancy\Commands\Concerns\MaybeTenantAware;
use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Console\Command;

class TenantMaybeNoopCommand extends Command
{
    use MaybeTenantAware;

    protected $signature = 'tenant:maybe-noop {--tenant=*}';

    protected $description = 'Execute maybe noop for tenant(s)';

    public function handle(): void
    {
        $this->line('Tenant ID is '.Tenant::current()?->id);
    }
}
