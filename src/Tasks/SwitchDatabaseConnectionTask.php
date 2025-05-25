<?php

namespace Glimmer\Tenancy\Tasks;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchDatabaseConnectionTask implements SwitchTenantTask
{
    protected Collection $addedConnections;

    public function __construct(
        #[\Illuminate\Container\Attributes\Config('database.default')]
        protected string $defaultConnection,
        #[\Illuminate\Container\Attributes\Config('database.connections')]
        protected array $connectionsConfig,
        #[\Illuminate\Container\Attributes\Config('multitenancy.tenant_database_connection_name')]
        protected ?string $tenantConnection,
        #[\Illuminate\Container\Attributes\Config('queue.connections')]
        protected array|Collection $queueConnections,
    ) {
        $this->addedConnections = new Collection([]);
        $this->queueConnections = new Collection($this->queueConnections);

        Config::set('multitenancy.landlord_database_connection_name', $this->defaultConnection);

        if (isset($_SERVER['LARAVEL_OCTANE'])) {
            app(IsTenant::class)->each(fn (IsTenant $tenant) => $this->createConnection($tenant));
        }
    }

    /**
     * @throws RuntimeException
     */
    protected function createConnection(IsTenant $tenant): void
    {
        $databaseName = $tenant->getDatabaseName();
        $databaseConnection = $tenant->database_connection ?? $this->tenantConnection ?? $this->defaultConnection;

        if (! is_file($databaseName) && ! is_dir($databaseName)) {
            if ($this->connectionsConfig[$databaseConnection]['driver'] === 'sqlite' && ! str_contains($databaseName,
                '.sqlite')) {
                $databaseName = database_path($databaseName.'.sqlite');
            }
        }

        if (! isset($this->connectionsConfig[$databaseConnection])) {
            throw new RuntimeException("Database connection [$databaseConnection] not found.");
        }

        Config::set("database.connections.{$tenant->getKey()}", array_merge(
            $this->connectionsConfig[$databaseConnection],
            $tenant->connection_config?->toArray() ?? [],
            ['database' => $databaseName]
        ));

        $this->addedConnections->put($tenant->getKey(), true);
    }

    public function makeCurrent(IsTenant $tenant): void
    {
        if (! $this->addedConnections->get($tenant->getKey())) {
            $this->createConnection($tenant);
        }

        Config::set('multitenancy.tenant_database_connection_name', $tenant->getKey());
        $this->setQueueConnection($this->defaultConnection);
        DB::setDefaultConnection($tenant->getKey());
    }

    public function setQueueConnection($connection): void
    {
        $this->queueConnections
            ->filter(fn ($c) => $c['driver'] == 'database')
            ->filter(fn ($c) => $c['connection'] == null)
            ->each(fn ($c, $name) => Config::set("queue.connections.$name.connection", $connection));
    }

    public function forgetCurrent(): void
    {
        Config::set('multitenancy.tenant_database_connection_name', null);
        $this->setQueueConnection(null);
        DB::setDefaultConnection($this->defaultConnection);
    }
}
