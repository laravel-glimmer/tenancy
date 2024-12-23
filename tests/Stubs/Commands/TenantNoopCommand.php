<?php

namespace Glimmer\Tenancy\Tests\Stubs\Commands;

use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Console\Command;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class TenantNoopCommand extends Command
{
    use TenantAware;

    protected $signature = 'tenant:noop {--modify=false} {--tenant=*}';

    protected $description = 'Execute noop for tenant(s)';

    public function handle(): void
    {
        if ($this->option('modify')) {
            $this->line('Modifying tenant(s)');
            Tenant::current()->update(['hosts' => ['modified']]);
        }

        $this->line('Tenant ID is '.Tenant::current()->id);
    }
}
