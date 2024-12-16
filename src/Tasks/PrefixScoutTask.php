<?php

namespace Glimmer\Tenancy\Tasks;

use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class PrefixScoutTask implements SwitchTenantTask
{
    public function __construct(
        #[\Illuminate\Container\Attributes\Config('scout.prefix')]
        protected string $originalPrefix,
        #[\Illuminate\Container\Attributes\Config('database.default')]
        protected string $defaultConnection,
    ) {}

    public function makeCurrent(IsTenant $tenant): void
    {
        Config::set('scout.database', $tenant->getKey());
        Config::set('scout.prefix', $tenant->getKey().'.'.$this->originalPrefix);
    }

    public function forgetCurrent(): void
    {
        Config::set('scout.database', $this->defaultConnection);
        Config::set('scout.prefix', $this->originalPrefix);
    }
}
