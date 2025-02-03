<?php

namespace App\Http\Controllers;

use App\Data\Token\TokenFormData;
use App\Models\Listing;
use Illuminate\Http\Request;

class TokenController
{
    public function download()
    {
        $token = session('listing_token');

        if (!$token) {
            return back()->withErrors(['token' => 'No token found in session']);
        }

        $filename = 'LostCityMarketsToken.txt';
        $content = "Token: " . $token;

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function store(TokenFormData $data)
    {
        $exists = Listing::where('token', $data->token)->exists();

        if (!$exists) {
            return back()->withErrors(['token' => 'Invalid token']);
        }

        session(['listing_token' => $data->token]);

        return to_route('listings.index')->success('Logged in with token successfully');
    }
}
