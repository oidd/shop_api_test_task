<?php

namespace App\Service\ResourceServices;

use App\Contracts\ResourceServiceContract;
use App\Models\Category;
use App\Traits\ResourceDefaultMethods;
use App\Utils\Filtering\Filter;
use Illuminate\Database\Eloquent\Builder;

class CategoryService implements ResourceServiceContract
{
    use ResourceDefaultMethods;

    public function getModel()
    {
        return new Category();
    }

    public function index(array $filterBy, array $sortBy): Builder
    {
        $filter = new Filter([
            'name' =>
                fn ($data, $req) => $data->whereRaw("name ilike '%{$req}%'"),
            ]);

        return $filter->apply(Category::query(),
            filterRequirements: $filterBy,
            sortRequirements: $sortBy,
        );
    }
}
