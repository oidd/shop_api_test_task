<?php

namespace App\Service\ResourceServices;

use App\Contracts\HaveOrdersContract;
use App\Contracts\ResourceServiceContract;
use App\Events\OrderCancelled;
use App\Events\OrderSaved;
use App\Events\OrderUpdated;
use App\Exceptions\CantSatisfyShipmentException;
use App\Exceptions\UserCantAffordOrderException;
use App\Models\Order;
use App\Traits\HandlesMoney;
use App\Traits\ResourceDefaultMethods;
use App\Utils\Filtering\Filter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService implements ResourceServiceContract
{
    use ResourceDefaultMethods;
    use HandlesMoney;

    public function getModel()
    {
        return new Order();
    }

    public function index($filterBy, $sortBy)
    {
        $filter = new Filter([
            'amount_range' =>
                fn ($data, $req) => $data->whereBetween('amount', array_map(fn ($v) => (float) $v * 100, explode('-', $req)) ),
            'status' =>
                fn ($data, $req) => $data->whereIn('status', $req),
            'customer_id' =>
                fn ($data, $req) => $data->where('customer_id', $req),
            'created_at_range' =>
                fn ($data, $req) => $data->whereBetween('created_at', array_map(fn ($v) => Carbon::createFromFormat('Y.m.d', $v), explode('-', $req)))
        ]);

        return $filter->apply(Order::query(),
            filterRequirements: $filterBy ?? array(),
            sortRequirements: $sortBy ?? array(),
        );
    }

    public function getOrdersForUser(HaveOrdersContract $user, $filterBy, $sortBy)
    {
        return $this->index(array_merge(['customer_id' => $user->id], $filterBy), $sortBy);
    }

    public function store(array $params)
    {
        DB::beginTransaction();

        $order = Order::create([
            'customer_id' => $params['user_id'],
        ]);

        unset($params['user_id']);

        foreach ($params as $v)
            $order->products()->attach($order->id, ['product_id' => $v['id'], 'count' => $v['count']]);

        $this->proceedPayment($order);
        $this->satisfyShipment($order);

//      saves the entry in db AND all it's relations
        $order->push();

//      the transaction is automatically rollbacks when exception occur, so no need to try-catch
        DB::commit();

        OrderSaved::dispatch($order);

        return $order->refresh();
    }

    private function satisfyShipment(Order $uncommitedOrder): bool
    {
        $products = $uncommitedOrder->products;

        foreach ($products as $product)
        {
            if ($product->pivot->count > $product->stock)
                throw new CantSatisfyShipmentException();

            $product->stock -= $product->pivot->count;
        }

        return true;
    }

    private function rollbackShipment(Order $order)
    {
        $products = $order->products;

        foreach ($products as $product)
            $product->stock += $product->pivot->count;

        return true;
    }

    private function proceedPayment(Order $uncommitedOrder)
    {
        $user = $uncommitedOrder->customer;

        if ($user->addToBalance($uncommitedOrder->amount * (-1)) < 0)
            throw new UserCantAffordOrderException(code: 422);

        return true;
    }

    private function rollbackPayment(Order $order)
    {
        $user = $order->customer;

        $user->addToBalance($order->amount);

        return true;
    }

    public function proceedRefund(Order $order)
    {
        DB::beginTransaction();

        $this->rollbackShipment($order);
        $this->rollbackPayment($order);

        $order->push();

        DB::commit();

        return $order->refresh();
    }

    public function update($model, array $params)
    {
        $model->update($params);

        OrderUpdated::dispatch($model);
        OrderCancelled::dispatchIf($params['status'] == 'cancelled', $model);

        return tap($model)->save();
    }
}
