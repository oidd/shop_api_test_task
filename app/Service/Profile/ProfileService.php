<?php

namespace App\Service\Profile;

use App\Models\Customer;
use App\Utils\Filtering\Filter;

class ProfileService
{
    public function index($filterBy, $sortBy)
    {
        $filter = new Filter([
            'name' =>
                fn ($data, $req) => $data->whereRaw("name ilike '%{$req}%'"),
            'email' =>
                fn ($data, $req) => $data->whereRaw("email ilike '%{$req}%'"),
            'balance_range' =>
                fn ($data, $req) => $data->whereBetween('balance', explode('-', $req)),
        ]);

        $filter->apply(Customer::query(),
            filterRequirements: $filterBy,
            sortRequirements: $sortBy
        );
    }
}
