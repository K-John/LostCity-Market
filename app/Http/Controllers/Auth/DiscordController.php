<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Username;
use App\Services\UsernameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    public function redirectToDiscord()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function handleDiscordCallback()
    {
        try {
            $discordUser = Socialite::driver('discord')->user();

            $user = User::updateOrCreate(
                ['discord_id' => $discordUser->id],
                [
                    'name' => $discordUser->name ?? $discordUser->nickname,
                    'email' => $discordUser->email
                ]
            );

            Auth::login($user, true);

            try {
                UsernameService::updateUsernamesForUser($user);
            } catch (\Exception $e) {
                return to_route('listings.index')->error('An error occurred while updating your usernames');
            }

            return to_route('listings.index')->success('You have successfully logged in');

        } catch (\Exception $e) {
            return to_route('login.index')->error('An error occurred while logging in with Discord');
        }
    }
}