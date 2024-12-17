<?php

namespace Glimmer\Tenancy\Jobs;

use Glimmer\Tenancy\Jobs\Concerns\MaybeTenantAware;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use InvalidArgumentException;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Landlord;

class SynchronizeSharedModel implements MaybeTenantAware, ShouldQueue
{
    use Queueable;

    public int $tries = 0;

    public function __construct(
        public string $modelClass,
        public int|string $modelKey,
        public bool $delete = false,
    ) {
        if (! class_exists($this->modelClass) || ! is_subclass_of($this->modelClass, Model::class)) {
            throw new InvalidArgumentException("The class {$this->modelClass} must be a valid Eloquent model.");
        }
    }

    public function handle(): void
    {
        if ($this->delete) {
            $modelAction = fn () => $this->deleteModel();
        } else {
            $model = $this->modelClass::findOrFail($this->modelKey);
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
        $this->modelClass::find($this->modelKey)?->deleteQuietly();
    }

    public function updateOrInsertModel(Model $model): void
    {
        $model::updateOrInsert(
            [$model->getKeyName() => $model->getKey()],
            $model->makeHidden($model->getKeyName())->toArray()
        );
    }
}
