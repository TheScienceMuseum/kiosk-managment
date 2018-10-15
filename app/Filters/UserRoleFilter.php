<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class UserRoleFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas('roles', function (Builder $query) use ($value) {
            $query->where('name', $value);
        });
    }
}