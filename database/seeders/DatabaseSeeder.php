<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Listing;
use App\Models\User;
use App\Models\Username;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 users
        User::factory()
            ->count(20)
            ->create()
            ->each(function ($user) {
                // Each user gets 1–3 usernames
                $usernames = Username::factory()
                    ->count(rand(1, 3))
                    ->create(['user_id' => $user->id]);

                // Each username makes 2–5 listings
                foreach ($usernames as $username) {
                    Listing::withoutEvents(function () use ($username) {
                        Listing::factory()
                            ->count(rand(2, 10))
                            ->create([
                                'username' => $username->username
                            ]);
                    });
                }
            });
    }
}
