<?php

namespace App\Listeners;

use App\Events\OrderSaved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewOrderNotification
{
    public function handle(OrderSaved $event): void
    {
//        dd($event->order);
    }
}
