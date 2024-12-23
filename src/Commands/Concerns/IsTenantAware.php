<?php

namespace Glimmer\Tenancy\Commands\Concerns;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Spatie\Multitenancy\Contracts\IsTenant;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @mixin Command
 */
trait IsTenantAware
{
    use UsesMultitenancyConfig;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tenants = Arr::wrap($this->option('tenant'));

        $tenantQuery = app(IsTenant::class)::query()
            ->when(! blank($tenants), function ($query) use ($tenants) {
                collect($this->getTenantArtisanSearchFields())
                    ->each(fn ($field) => $query->orWhereIn($field, $tenants));
            });

        if ($tenantQuery->count() === 0) {
            $this->error('No tenant(s) found.');

            return -1;
        }

        $this->info('Glimmer Tenancy aware command');

        $tenantDriver = Config::get('database.connections.'.app(IsTenant::class)->getConnectionName().'.driver');

        return $tenantQuery
            ->when($tenantDriver == 'sqlite', fn ($q) => $q->get(), fn ($q) => $q->cursor())
            ->map(fn (IsTenant $tenant) => $tenant->execute(fn () => (int) $this->laravel->call([$this, 'handle'])))
            ->sum();
    }
}
