<?php

namespace App\Filters;

use App\KioskLog;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class UnseenKioskLogErrorFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $value ?
            $query->whereHas('logs', function ($where) {
                $where->has('seen_by_user', '<', 1);
            }) : $query;
    }
}