<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();
        
        return [
            'order_id' => null, // This should be set when creating
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, 3),
            'price' => $product->price,
        ];
    }
} 