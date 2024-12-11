<?php

namespace Tests;

use Glimmer\Tenancy\TenancyServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            TenancyServiceProvider::class,
        ];
    }
}
