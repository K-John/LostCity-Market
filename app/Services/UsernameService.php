<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\User;
use App\Models\Username;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsernameService
{
    public static function updateUsernamesForUser(User $user): void
    {
        $jwtToken = JWTAuth::fromUser($user);

        $response = Http::withToken($jwtToken)
            ->get(config('services.username_api.url'), [
                'email' => $user->email
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch usernames');
        }
        $usernames = $response->json('usernames');

        $existingUsernames = Username::where('user_id', $user->id)->pluck('username')->toArray();
        $newUsernames = array_diff($usernames, $existingUsernames);
        $usernamesToDelete = array_diff($existingUsernames, $usernames);

        Username::where('user_id', $user->id)->whereIn('username', $usernamesToDelete)->delete();

        foreach ($newUsernames as $username) {
            Username::create([
                'user_id' => $user->id,
                'username' => $username,
            ]);

            // Update old listings with new user_id
            Listing::withoutTimestamps(function() use ($username, $user) {
                Listing::whereRaw("LOWER(REPLACE(username, ' ', '_')) = LOWER(?)", [$username])->update(['user_id' => $user->id, 'username' => $username]);
            });
        }
    }

    public static function getAuthenticatedUsernames(): array
    {
        return Auth::user()?->usernames->pluck('username')->toArray() ?? [];
    }
}
