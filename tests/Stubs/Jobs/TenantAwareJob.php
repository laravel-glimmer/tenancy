<?php

namespace Glimmer\Tenancy\Tests\Stubs\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Multitenancy\Jobs\TenantAware;

class TenantAwareJob implements ShouldQueue, TenantAware
{
    use Queueable;

    public function __construct() {}

    public function handle(): void
    {
        //
    }
}
