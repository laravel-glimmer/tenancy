<?php

namespace Glimmer\Tenancy\Traits;

use Glimmer\Tenancy\Jobs\SynchronizeSharedModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait IsSharedModel
 *
 * This trait is used to synchronize a model across landlord and all tenants when it is created, updated, or deleted.
 *
 * @mixin Model
 */
trait IsSharedModel
{
    public static function bootIsSharedModel(): void
    {
        static::saved(fn (self $model) => SynchronizeSharedModel::dispatch($model::class, $model->getKey()));
        static::deleted(fn (self $model) => SynchronizeSharedModel::dispatch($model::class, $model->getKey()));
    }
}
