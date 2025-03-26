<?php

namespace Glimmer\Tenancy\Jobs;

use Glimmer\Tenancy\Jobs\Concerns\MaybeTenantAware;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Queue\Queueable;
use InvalidArgumentException;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Landlord;

class SynchronizeSharedModel implements MaybeTenantAware, ShouldQueue
{
    use Queueable;

    public int $tries = 0;

    /**
     * @param  class-string<Model>  $modelClass
     */
    public function __construct(
        public string $modelClass,
        public int|string $modelKey,
    ) {
        if (! class_exists($this->modelClass) || ! is_subclass_of($this->modelClass, Model::class)) {
            throw new InvalidArgumentException("The class {$this->modelClass} must be a valid Eloquent model.");
        }
    }

    public function handle(): void
    {
        $model = $this->modelClass::query()->when(
            in_array(SoftDeletes::class, class_uses_recursive($this->modelClass)),
            fn (Builder $q) => $q->withTrashed()
        )->find($this->modelKey);

        if (! $model) {
            $modelAction = fn () => $this->deleteModel();
        } else {
            $modelAction = fn () => $this->updateOrInsertModel($model);
        }

        if (app(IsTenant::class)::checkCurrent()) {
            Landlord::execute($modelAction);
        }

        app(IsTenant::class)::whereKeyNot(app(IsTenant::class)::current()?->getKey())->get()->eachCurrent(
            fn (IsTenant $tenant) => $tenant->execute($modelAction)
        );
    }

    public function deleteModel(): void
    {
        $this->modelClass::query()->find($this->modelKey)?->deleteQuietly();
    }

    public function updateOrInsertModel(Model $model): void
    {
        $model::query()->updateOrInsert(
            [$model->getKeyName() => $model->getKey()],
            $model->makeHidden($model->getKeyName())->toArray()
        );
    }
}
