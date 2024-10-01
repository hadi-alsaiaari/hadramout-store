<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DeductProductQuantity
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
    public function handle($event): void
    {
        $order = $event->order;
        // UPDATE products SET quantity = quantity - 1
        foreach ($order->products as $product) {
            $product->decrement('quantity', $product->order_item->quantity);

            // Product::where('id', '=', $item->product_id)
            //     ->update([
            //         'quantity' => DB::raw("quantity - {$item->quantity}")
            //     ]);
        }


        // $items = $event->items;
        // foreach ($items as $store_id => $cart_items) {
        //     foreach ($cart_items as $item) {
        //         Product::where('id', '=', $item->product_id, 'AND', 'store_id', '=', $store_id)
        //             ->update([
        //                 'quantity' => DB::raw("quantity - {$item->quantity}")
        //             ]);
        //     }
        // }
    }
}
