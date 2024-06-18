<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Service\ResourceServices\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RefundCancelledOrder implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        public OrderService $orderService
    )
    {}

    public function handle(OrderCancelled $event): void
    {
        $this->orderService->proceedRefund($event->order);
    }
}
