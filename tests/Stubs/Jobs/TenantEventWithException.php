<?php

namespace Glimmer\Tenancy\Tests\Stubs\Jobs;

use Exception;
use Glimmer\Tenancy\Jobs\Concerns\TenantEventQueue;

class TenantEventWithException extends TenantEventQueue
{
    public function handle(): void
    {
        throw new Exception('Tenant event exception');
    }
}
