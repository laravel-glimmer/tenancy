<?php

namespace Glimmer\Tenancy\Traits;

use Closure;
use Illuminate\Support\Arr;

trait TenantCallbackFixForConcurrency
{
    public function callback(callable $callable): Closure
    {
        $tenant = $this;

        return fn () => $tenant->execute($callable);
    }

    /**
     * @return array<Closure>
     */
    public function callbacks(callable|array $tasks): array
    {
        return Arr::map(Arr::wrap($tasks), fn ($c) => $this->callback($c));
    }
}
