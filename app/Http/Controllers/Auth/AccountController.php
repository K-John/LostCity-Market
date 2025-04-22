<?php

namespace App\Http\Controllers\Auth;

use App\Pages\Auth\AccountIndexPage;
use App\Services\UsernameService;
use Illuminate\Http\Request;

class AccountController
{
    public function __invoke(Request $request)
    {
        return inertia('auth/account/index/page', new AccountIndexPage(
            name: $request->user()->name,
            register_date: $request->user()->created_at,
            listing_count: $request->user()->listings()->count(),
            usernames: UsernameService::getAuthenticatedUsernames(),
        ));
    }
}