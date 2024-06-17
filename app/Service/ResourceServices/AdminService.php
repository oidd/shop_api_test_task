<?php

namespace App\Service\ResourceServices;

use App\Contracts\ResourceServiceContract;
use App\Models\Admin;
use App\Traits\ResourceDefaultMethods;
use App\Utils\Filtering\Filter;

class AdminService implements ResourceServiceContract
{
    use ResourceDefaultMethods;

    public function getModel()
    {
        return new Admin();
    }

    public function index($filterBy, $sortBy)
    {
        $filter = new Filter([
            'name' =>
                fn ($data, $req) => $data->whereRaw("name ilike '%{$req}%'"),
            'email' =>
                fn ($data, $req) => $data->whereRaw("email ilike '%{$req}%'"),
        ]);

        return $filter->apply(Admin::query(),
            filterRequirements: $filterBy,
            sortRequirements: $sortBy,
        );
    }
}
