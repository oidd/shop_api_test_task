<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\DestroyRequest;
use App\Http\Requests\Order\IndexRequest;
use App\Http\Requests\Order\RefundRequest;
use App\Http\Requests\Order\ShowRequest;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Service\ResourceServices\OrderService;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $service
    )
    {}

    public function index(IndexRequest $request)
    {
        return response()->json(
            $this->retrieveAsResourceAndPaginate(
                OrderResource::class,
                $this->service->index(
                    $request->safe()->except(['sort']) ?? array(),
                    $request->input(['sort']) ?? array()),
                $request
            )
        );
    }

    public function store(StoreRequest $request)
    {
        return response()->json(new OrderResource(
            $this->service->store(array_merge($request->validated()['products'], ['user_id' => $request->user()->id]))
        ));
    }

    public function refund(RefundRequest $request, Order $order)
    {
        return response()->json($this->service->proceedRefund($order));
    }

    public function show(ShowRequest $request, Order $order)
    {
        return response()->json(
            new OrderResource($order)
        );
    }

    public function update(UpdateRequest $request, Order $order)
    {
        return response()->json(
            new OrderResource(
                $this->service->update($order, $request->validated())
            )
        );
    }

    public function destroy(DestroyRequest $request, Order $order)
    {
        return response()->json(
            new OrderResource($this->service->destroy($order))
        );
    }
}
