<?php

namespace Glimmer\Tenancy\Tests\Stubs\Models;

use Glimmer\Tenancy\Traits\IsSharedModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use IsSharedModel, Searchable;

    protected $guarded = ['id'];

    public static function bootSearchable() {}

    public function shouldBeSearchable(): false
    {
        return false;
    }
}
