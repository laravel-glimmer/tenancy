<?php

namespace Glimmer\Tenancy\Traits;

use Closure;

trait ImplementsConcurrency
{
    public function concurrently(Closure|array $tasks): array
    {
        $tenantKey = $this->getKey();

        return array_map(function ($task) use ($tenantKey) {
            return fn () => static::find($tenantKey)->execute($task);
        }, (array) $tasks);
    }

    public static function concurrency(Closure|array $tasks): array
    {
        return static::current()->concurrently($tasks);
    }
}
