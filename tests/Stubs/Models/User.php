<?php

namespace Glimmer\Tenancy\Tests\Stubs\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use Searchable;
}
