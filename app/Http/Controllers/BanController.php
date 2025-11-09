<?php

namespace App\Http\Controllers;

use App\Models\Username;

class BanController
{
    public function store(Username $username)
    {
        $user = $username->user;

        $user->update([
            'is_banned' => true,
            'banned_at' => now(),
        ]);

        return back()->success("{$username->username} has been banned");
    }

    public function destroy(Username $username)
    {
        $user = $username->user;

        $user->update([
            'is_banned' => false,
            'banned_at' => null,
        ]);

        return back()->success("{$username->username} has been unbanned");
    }
}
