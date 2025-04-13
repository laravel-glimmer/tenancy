<?php

namespace Glimmer\Tenancy\Models;

use Glimmer\Tenancy\Traits\HasTenantEvents;
use Glimmer\Tenancy\Traits\ImplementsConcurrency;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class Tenant extends SpatieTenant
{
    use HasTenantEvents, ImplementsConcurrency, SoftDeletes;

    public $incrementing = false;

    public $usesUniqueIds = true;

    protected $keyType = 'string';

    protected $guarded = ['id'];

    public function getDatabaseName(): string
    {
        return $this->connection_config?->get('database') ?? strtolower(config('app.name')).'_'.$this->id;
    }

    protected function casts(): array
    {
        return [
            'hosts' => AsCollection::class,
            'connection_config' => AsCollection::class,
        ];
    }
}
