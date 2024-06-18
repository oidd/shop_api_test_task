<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use App\Models\Admin;
use App\Notifications\SendOrderCreatedNotification;
use App\Notifications\SendOrderUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderStatusUpdatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderUpdated $event): void
    {
        Notification::send(
            $event->order->customer,
            new SendOrderUpdatedNotification($event->order)
        );
    }
}
