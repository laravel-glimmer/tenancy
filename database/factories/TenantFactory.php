<?php

namespace Glimmer\Tenancy\Database\Factories;

use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->word(),
            'hosts' => [$this->faker->unique()->domainName()],
        ];
    }
}
