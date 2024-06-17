<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\IndexRequest;
use App\Http\Requests\Profile\OrdersRequest;
use App\Http\Requests\Profile\TopUpBalanceRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use App\Service\ResourceServices\OrderService;

class ProfileController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    )
    {}

    public function index(IndexRequest $request)
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

    public function topUpBalance(TopUpBalanceRequest $request)
    {
        return response()->json([
            'balance' => $request->user()->topUpBalance($request->safe()->input('amount')),
        ]);
    }
}
