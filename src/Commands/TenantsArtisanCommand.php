<?php

namespace Glimmer\Tenancy\Commands;

use Glimmer\Tenancy\Commands\Concerns\IsTenantAware;

class TenantsArtisanCommand extends \Spatie\Multitenancy\Commands\TenantsArtisanCommand
{
    use IsTenantAware;
}
