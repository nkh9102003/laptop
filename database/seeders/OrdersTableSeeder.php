<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all product IDs between 36 and 50
        $productIds = range(36, 50);

        // Get all user IDs except 3
        $userIds = User::whereNotIn('id', [3])->pluck('id')->toArray();

        // Generate 1000 orders
        for ($i = 0; $i < 1000; $i++) {
            $orderDate = $faker->dateTimeBetween('-5 years', 'now');
            $status = $faker->randomElement(['processing', 'paid', 'cancelled']);
            $paymentMethod = $faker->randomElement(['online', 'COD']);

            $order = Order::create([
                'user_id' => $faker->randomElement($userIds),
                'total' => 0, // We'll calculate this later
                'status' => $status,
                'payment_method' => $paymentMethod,
                'name' => $faker->name,
                'contact' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Generate 1-5 order items for each order
            $orderTotal = 0;
            $itemCount = $faker->numberBetween(1, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $product = Product::find($faker->randomElement($productIds));
                $quantity = $faker->numberBetween(1, 3);
                $price = $faker->numberBetween(600, 1300);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                $orderTotal += $quantity * $price;
            }

            // Update order total
            $order->update(['total' => $orderTotal]);

            // Create payment for paid orders
            if ($status === 'paid') {
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $orderTotal,
                    'payment_method' => $paymentMethod,
                    'payment_date' => $orderDate,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
            }
        }
    }
}