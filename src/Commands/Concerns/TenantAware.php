<?php

namespace Glimmer\Tenancy\Commands\Concerns;

/**
 * Acts as a replacement to Spatie/Multitenancy/Commands/Concerns/TenantAware
 */
trait TenantAware
{
    use IsTenantAware;
}
