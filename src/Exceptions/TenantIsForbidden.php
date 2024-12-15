<?php

namespace Glimmer\Tenancy\Exceptions;

use Exception;

class TenantIsForbidden extends Exception
{
    public static function make(): static
    {
        return new static('The request expected none tenant, but a tenant was set.');
    }
}
