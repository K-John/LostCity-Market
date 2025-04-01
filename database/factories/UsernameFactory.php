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
        $username = $this->generateValidUsername();

        // Ensure the username is unique
        while (Username::where('username', $username)->exists()) {
            $username = $this->generateValidUsername();
        }

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'username' => $username,
        ];
    }

    private function generateValidUsername(): string
    {
        $length = rand(1, 12);

        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789_';

        do {
            $username = '';
            for ($i = 0; $i < $length; $i++) {
                $username .= $chars[rand(0, strlen($chars) - 1)];
            }
        } while (
            $username[0] === '_' ||
            $username[strlen($username) - 1] === '_'
        );

        return $username;
    }
}
