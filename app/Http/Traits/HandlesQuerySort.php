<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HandlesQuerySort
{
    /**
     * Apply filtering and sorting to the given query builder.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $allowedSorts
     * @param string|null $defaultSort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyFilterSort(
        Request $request,
        Builder $builder,
        array $allowedSorts,
        ?string $defaultSort = null
    ): Builder {
        if ($request->filled('sort')) {
            $sortField = ltrim($request->input('sort'), '-');
            $order = $request->input('sort')[0] === '-' ? 'desc' : 'asc';

            if (in_array($sortField, $allowedSorts, true)) {
                $builder->orderBy($sortField, $order);
            }
            
        } elseif ($defaultSort) {
            $defaultSortField = ltrim($defaultSort, '-');
            $defaultSortOrder = $defaultSort[0] === '-' ? 'desc' : 'asc';

            if (in_array($defaultSortField, $allowedSorts, true)) {
                $builder->orderBy($defaultSortField, $defaultSortOrder);
            }
        }

        return $builder;
    }
}
