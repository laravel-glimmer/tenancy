<?php

namespace Glimmer\Tenancy\Tasks;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class PrefixFilesystemTask implements SwitchTenantTask
{
    protected string $originalStorage;

    protected array $originalPrefixes = [];

    public function __construct()
    {
        $this->originalStorage = storage_path();

        foreach (Config::get('filesystems.disks') as $disk => $config) {
            $this->originalPrefixes[$disk] = $config['prefix'] ?? '';
        }
    }

    public function makeCurrent(IsTenant $tenant): void
    {
        $this->setStoragePrefix($tenant->getKey());
    }

    public function forgetCurrent(): void
    {
        $this->setStoragePrefix(null);
    }

    private function setStoragePrefix(?string $prefix): void
    {
        foreach (Config::get('filesystems.disks') as $disk => $config) {
            $config['prefix'] = $this->originalPrefixes[$disk].($prefix ? "/$prefix" : '');
            Config::set(["filesystems.disks.$disk" => $config]);
            Storage::forgetDisk($disk);
        }

        App::useStoragePath($prefix ? $this->originalStorage."/$prefix" : $this->originalStorage);
    }
}
