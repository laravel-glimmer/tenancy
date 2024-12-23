<?php

namespace Glimmer\Tenancy\Commands\Concerns;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @mixin Command
 */
trait MaybeTenantAware
{
    use IsTenantAware {
        execute as protected parentExecute;
    }
    use UsesMultitenancyConfig;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tenants = Arr::wrap($this->option('tenant'));

        if (! blank($tenants) && $tenants !== ['null']) {
            return $this->parentExecute($input, $output);
        }

        $this->info('No tenant specified.');

        switch ($tenants === ['null'] ?
            'landlord' :
            $this->choice('Do you want to run this command for `landlord` or all `tenants`?', ['landlord', 'tenants'])
        ) {
            case 'tenants':
                return $this->parentExecute($input, $output);
            case 'landlord':
                $this->info('Running command without tenant context. (landlord)');

                return (int) $this->laravel->call([$this, 'handle']);
            default:
                $this->error('Invalid answer.');

                return -1;
        }
    }
}
