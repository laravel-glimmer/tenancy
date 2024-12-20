<?php

namespace Glimmer\Tenancy\Traits;

use Glimmer\Tenancy\Jobs\Concerns\TenantEventQueue;
use Illuminate\Foundation\Bus\PendingChain;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;

trait HasTenantEvents
{
    public static function bootHasTenantEvents(): void
    {
        collect(Config::get('multitenancy.tenant_events', []))->each(function ($array, $event) {
            $jobs = collect($array)->except('catch')
                ->filter(fn ($job) => class_exists($job) && is_subclass_of($job, TenantEventQueue::class));

            if ($jobs->count() < 1) {
                return;
            }

            static::$event(fn (self $model) => Bus::chain($jobs->map(fn ($job) => new $job($model)))
                ->when(isset($array['catch']), fn (PendingChain $chain) => $chain->catch($array['catch']))
                ->dispatch()
            );
        });
    }
}
