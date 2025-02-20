<?php

namespace App\Services;

use App\Models\User;
use App\Models\Username;
use Illuminate\Support\Facades\Auth;

class UsernameService
{
    public static function updateUsernamesForUser(User $user): void
    {
        // Get the user's usernames from Lost City
        $usernames = [];

        // Update the user's usernames
        $existingUsernames = Username::where('user_id', $user->id)->pluck('username')->toArray();
        $newUsernames = array_diff($usernames, $existingUsernames);
        $usernamesToDelete = array_diff($existingUsernames, $usernames);

        Username::where('user_id', $user->id)->whereIn('username', $usernamesToDelete)->delete();

        foreach ($newUsernames as $username) {
            Username::create([
                'user_id' => $user->id,
                'username' => $username,
            ]);
        }

        // For now, let's make 1-3 fake usernames
        if (empty($existingUsernames)) {
            Username::factory(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);
        }
    }

    public static function getAuthenticatedUsernames(): array
    {
        return Auth::check() ? Auth::user()->usernames->pluck('username')->toArray() ?? [] : [];
    }
}
