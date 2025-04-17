<?php

namespace Glimmer\Tenancy\Jobs\TenantEvents;

use Exception;
use Glimmer\Tenancy\Jobs\Concerns\TenantEventQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateDatabase extends TenantEventQueue
{
    public function handle(): void
    {
        $this->tenant->execute(function () {
            Artisan::call($this->databaseExists() ? 'migrate:fresh' : 'migrate', [
                '--force' => true,
                '--path' => 'database/migrations/tenant',
            ]);
        });
    }

    public function databaseExists(): bool
    {
        if (Context::get('migrate:fresh', false)) {
            return $this->tenant->execute(function () {
                try {
                    DB::connection()->getPdo();
                    Schema::hasTable('migrations');
                    DB::disconnect();

                    return true;
                } catch (Exception) {
                    return false;
                }
            });
        }

        return false;
    }
}
