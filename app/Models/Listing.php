<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    public $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            // Check if a token exists in the session
            $token = Session::get('listing_token');

            // If not, generate a new one and store it in session and cookie
            if (!$token) {
                $token = Str::uuid()->toString();
                Session::put('listing_token', $token);
                Cookie::queue(Cookie::forever('listing_token', $token));
            }

            // Assign token to the listing
            $listing->token = $token;
        });
    }
}
