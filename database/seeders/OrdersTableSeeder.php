<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Faker\Factory as Faker;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Create 100 orders
        Order::factory()
            ->count(100)
            ->create()
            ->each(function ($order) {
                // Add 1-4 items to each order
                $itemCount = rand(1, 4);
                $orderTotal = 0;
                
                // Get random unique products
                $products = Product::inRandomOrder()->take($itemCount)->get();
                
                foreach ($products as $product) {
                    $quantity = rand(1, 3);
                    $orderItem = OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->created_at,
                    ]);
                    
                    $orderTotal += $quantity * $product->price;
                }

                // Update order total
                $order->update(['total' => $orderTotal]);

                // Create payment for paid orders
                if ($order->status === 'paid') {
                    Payment::create([
                        'order_id' => $order->id,
                        'amount' => $orderTotal,
                        'payment_method' => $order->payment_method,
                        'payment_date' => $order->created_at,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->created_at,
                    ]);
                }
            });
    }
}