<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Requests\Profile\OrdersRequest;
use App\Http\Requests\Profile\TopUpBalanceRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use App\Service\ResourceServices\CustomerService;
use App\Service\ResourceServices\OrderService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private CustomerService $customerService
    )
    {}

    public function index(Request $request)
    {
        return response()->json(
            new CustomerResource($request->user()),
        );
    }

    public function orders(OrdersRequest $request)
    {
        return response()->json(
            $this->retrieveAsResourceAndPaginate(
                OrderResource::class,
                $this->orderService->getOrdersForUser(
                    $request->user(),
                    $request->safe()->except(['sort']),
                    $request->safe()->input(['sort'])
                ),
                $request
            )
        );
    }

    public function update(UpdateRequest $request)
    {
        return response()->json(
            new CustomerResource($this->customerService->update($request->user(), $request->validated()))
        );
    }

    public function topUpBalance(TopUpBalanceRequest $request)
    {
        return response()->json([
            'balance' => $request->user()->addToBalance($request->safe()->input('amount')),
        ]);
    }
}
