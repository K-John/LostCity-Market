<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    public function definition(): array
    {
        // Get a random item (this assumes items exist already)
        $item = Item::inRandomOrder()->first();

        return [
            'type' => $this->faker->randomElement(['buy', 'sell']),
            'price' => $this->faker->numberBetween(10, 100000),
            'quantity' => $this->faker->numberBetween(1, 100),
            'username' => $this->faker->userName, // Will be overridden by the seeder
            'notes' => $this->faker->optional()->sentence(),
            'item_id' => $item?->id
        ];
    }
}
