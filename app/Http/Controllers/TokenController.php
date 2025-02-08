<?php

namespace App\Http\Controllers;

use App\Data\Listing\ListingData;
use App\Data\Token\TokenFormData;
use App\Models\Listing;
use App\Pages\TokensShowPage;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

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

    public function show(Listing $listing)
    {
        $maskedToken = $listing->token ? substr($listing->token, 0, 4) . '****-****-****' . substr($listing->token, -4) : "";

        $listings = Listing::with('item')
            ->where('token', $listing->token)
            ->where('updated_at', '>=', now()->subDays(2))
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return inertia('tokens/show/page', new TokensShowPage(
            token: $maskedToken,
            listings: ListingData::collect($listings, PaginatedDataCollection::class)
        ));
    }
}
