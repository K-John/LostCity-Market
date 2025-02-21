<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function index()
    {
        if (Auth::check()) {
            return back()->success('You are already logged in with Discord');
        }

        return inertia('login/index/page');
    }

    public function destroy(User $user)
    {
        Auth::logout();

        return back()->success('You have successfully logged out');
    }
}
