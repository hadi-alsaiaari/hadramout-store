<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\OrderCreatedNotification;

class SendOrderCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //$store = $event->order->store;
        $order = $event->order;

        $user = User::where('store_id', $order->store_id)->first();
        
        if ($user) {
            $user->notify(new OrderCreatedNotification($order));
        }
        // $user->notifyNow(new OrderCreatedNotification($order)); no طابور

        // $users = User::where('store_id', $order->store_id)->get();
        // Notification::send($users, new OrderCreatedNotification($order));
    }
}
