<?php

namespace Glimmer\Tenancy\Jobs\TenantEvents;

use Glimmer\Tenancy\Jobs\Concerns\TenantEventQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SeedDatabase extends TenantEventQueue
{
    public function handle(): void
    {
        $this->tenant->execute(function () {
            $exists = File::exists(database_path('seeders/tenant/DatabaseSeeder.php'));

            Artisan::call('db:seed', array_merge(
                ['--force' => true],
                $exists ? ['--class' => 'Database\\Seeders\\tenant\\DatabaseSeeder'] : []
            ));
        });
    }
}
