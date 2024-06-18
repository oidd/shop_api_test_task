<?php

namespace App\Listeners;

use App\Events\OrderSaved;
use App\Models\Admin;
use App\Notifications\SendOrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewOrderNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderSaved $event): void
    {
        Notification::send(
            Admin::all(),
            new SendOrderCreatedNotification($event->order)
        );
    }
}
