<?php

namespace Database\Factories;

use App\Models\Username;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsernameFactory extends Factory
{
    protected $model = Username::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'username' => strtolower($this->faker->regexify('[a-z1-9_]{3,12}')),
        ];
    }
}
