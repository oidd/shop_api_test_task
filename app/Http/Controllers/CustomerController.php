<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Customer\IndexRequest;
use App\Http\Requests\Customer\ShowRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Service\Auth\AuthService;
use App\Service\ResourceServices\CustomerService;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerService $service,
        private AuthService $authService
    )
    {}

    public function index(IndexRequest $request)
    {
        return response()->json(
            $this->retrieveAsResourceAndPaginate(
                CustomerResource::class,
                $this->service->index(
                    $request->safe()->except(['sort']) ?? array(),
                    $request->input(['sort']) ?? array()),
                $request
            )
        );
    }

    public function show(ShowRequest $request, Customer $customer)
    {
        return response()->json(
            new CustomerResource($customer)
        );
    }

    public function store(RegisterRequest $request)
    {
        return response()->json(
            new CustomerResource($this->authService->register($request->validated()))
        );
    }

    public function update(UpdateRequest $request, Customer $customer)
    {
        return response()->json(
            new CustomerResource($this->service->update($customer, $request->validated()))
        );
    }

    public function destroy(Customer $customer)
    {
        return response()->json(
            new CustomerResource($this->service->destroy($customer))
        );
    }
}
