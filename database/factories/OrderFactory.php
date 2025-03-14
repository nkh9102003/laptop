<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $orderDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $user = User::where('role', 'customer')->inRandomOrder()->first();
        return [
            'user_id' => $user->id,
            'total' => 0, // Will be calculated after adding items
            'status' => $this->faker->randomElement(['processing', 'paid', 'cancelled']),
            'payment_method' => $this->faker->randomElement(['online', 'COD']),
            'name' => $user->name,
            'contact' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress() . ', ' . 
                        $this->faker->city() . ', ' . 
                        $this->faker->state() . ' ' . 
                        $this->faker->postcode(),
            'created_at' => $orderDate,
            'updated_at' => $orderDate,
        ];
    }

    /**
     * Indicate that the order is paid
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid'
        ]);
    }

    /**
     * Indicate that the order is processing
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing'
        ]);
    }

    /**
     * Indicate that the order is cancelled
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled'
        ]);
    }
} 