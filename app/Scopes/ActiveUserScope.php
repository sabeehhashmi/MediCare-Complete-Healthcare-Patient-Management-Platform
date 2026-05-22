<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveUserScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Assuming 'is_active' is a boolean field and 'deleted_at' is for soft deletes
        $builder->where('active', 1)->where('deleted','!=',1);
    }
}
