<?php

namespace Glimmer\Tenancy\Jobs\TenantEvents;

use Glimmer\Tenancy\Jobs\Concerns\TenantEventQueue;
use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;
use Spatie\Multitenancy\Contracts\IsTenant;

class SeedDatabase extends TenantEventQueue
{
    public string $seeder = 'Database\\Seeders\\tenant\\DatabaseSeeder';

    public bool $customSeeder = false;

    public function __construct(IsTenant|Tenant $tenant, ?string $seeder = null)
    {
        parent::__construct($tenant);

        if ($seeder) {
            $this->seeder = $seeder;
            $this->customSeeder = true;
        }
    }

    public function displayName(): string
    {
        return $this->customSeeder ? $this->seeder : self::class;
    }

    public function handle(): void
    {
        $this->tenant->execute(function () {
            $exists = class_exists($this->seeder);

            if ($this->customSeeder && ! $exists) {
                throw new RuntimeException("Seeder class $this->seeder does not exist.");
            }

            Artisan::call('db:seed', array_merge(
                ['--force' => true],
                $exists ? ['--class' => $this->seeder] : []
            ));
        });
    }
}
