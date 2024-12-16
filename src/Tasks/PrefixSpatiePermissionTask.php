<?php

namespace Glimmer\Tenancy\Tasks;

use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use Spatie\Permission\PermissionRegistrar;

class PrefixSpatiePermissionTask implements SwitchTenantTask
{
    public function __construct(
        #[\Illuminate\Container\Attributes\Config('permission.cache.key')]
        public string $originalKey,
        protected PermissionRegistrar $permissionRegistrar,
    ) {}

    public function makeCurrent(IsTenant $tenant): void
    {
        $this->setCacheKey($this->originalKey.'.'.$tenant->getKey());
    }

    public function forgetCurrent(): void
    {
        $this->setCacheKey($this->originalKey);
    }

    private function setCacheKey(string $key): void
    {
        Config::set('permission.cache.key', $key);
        $this->permissionRegistrar->cacheKey = $key;
    }
}
