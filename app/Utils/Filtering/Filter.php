<?php

namespace App\Utils\Filtering;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

// this is a little sketchy, but I can't afford writing something more complex.
// I'd love to merge filtering logic with validation logic in form requests,
// but can't come up with the idea of how to.

class Filter
{
    public function __construct(
        private array $filters,
    )
    {}

    public function apply(
        Builder $builder,
        array $filterRequirements,
        array $sortRequirements
    ): Builder
    {
        foreach ($filterRequirements as $k => $v)
            $builder = $this->filters[$k]($builder, $v);

        if (!empty($sortRequirements))
            foreach ($sortRequirements as $v)
                $builder = ($v[0] == '-')
                    ? $builder->orderBy(substr($v, 1), 'desc')
                    : $builder->orderBy($v, 'asc');
        else
            $builder = $builder->orderBy('id', 'asc');

        return $builder;
    }
}
