<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class PromoteController extends Controller
{
    public function store(User $user)
    {
        $user->update([
            'is_admin' => true,
        ]);

        return back()->success("{$user->name} has been promoted to admin");
    }

    public function destroy(User $user)
    {
        $user->update([
            'is_admin' => false,
        ]);

        return back()->success("{$user->name} has been demoted to noob");
    }
}
