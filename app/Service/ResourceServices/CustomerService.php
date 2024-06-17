<?php

namespace App\Service\ResourceServices;

use App\Contracts\ResourceServiceContract;
use App\Models\Customer;
use App\Traits\ResourceDefaultMethods;
use App\Utils\Filtering\Filter;

class CustomerService implements ResourceServiceContract
{
    use ResourceDefaultMethods;

    public function getModel()
    {
        return new Customer();
    }

    public function index(array $filterBy, array $sortBy)
    {
        $filter = new Filter([
            'name' =>
                fn ($data, $req) => $data->whereRaw("name ilike '%{$req}%'"),
            'email' =>
                fn ($data, $req) => $data->whereRaw("email ilike '%{$req}%'"),
            'balance_range' =>
                fn ($data, $req) => $data->whereBetween('balance', explode('-', $req))
        ]);

        return $filter->apply(Customer::query(),
            filterRequirements: $filterBy,
            sortRequirements: $sortBy,
        );
    }
}
