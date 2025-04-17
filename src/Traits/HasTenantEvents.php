<?php

namespace Glimmer\Tenancy\Traits;

use Glimmer\Tenancy\Jobs\Concerns\TenantEventQueue;
use Illuminate\Foundation\Bus\PendingChain;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Context;

trait HasTenantEvents
{
    public static function bootHasTenantEvents(): void
    {
        collect(Config::get('multitenancy.tenant_events', []))->each(function ($array, $event) {
            $jobs = collect($array)->except('catch')
                ->filter(fn($job) => class_exists($job) && is_subclass_of($job, TenantEventQueue::class));

            if ($jobs->count() < 1) {
                return;
            }

            self::isMigrateFresh();

            static::$event(fn(self $model) => Bus::chain($jobs->map(fn($job) => new $job($model)))
                ->when(isset($array['catch']), fn(PendingChain $chain) => $chain->catch($array['catch']))
                ->dispatch()
            );
        });
    }

    protected static function isMigrateFresh(): void
    {
        if (app()->runningInConsole() && app()->environment('local') && in_array(['migrate:fresh'], $_SERVER['argv'])) {
            Context::add('migrate:fresh', true);
        }
    }
}
