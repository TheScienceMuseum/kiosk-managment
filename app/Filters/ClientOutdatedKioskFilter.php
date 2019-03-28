<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ClientOutdatedKioskFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $value ?
            $query->where('client_version', '!=', config('kiosk.client-version')) :
            $query;
    }
}