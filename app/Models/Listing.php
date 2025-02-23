<?php

namespace App\Models;

use ConsoleTVs\Profanity\Facades\Profanity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Listing extends Model
{
    use HasFactory;

    public $guarded = [];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            // Get the authenticated user
            $user = Auth::user();

            // Add listing user_id if the authenticated user has the same username
            if ($user && $user->usernames->contains('username', $listing->username)) {
                $listing->user_id = $user->id;
            }
            
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

            $listing->filterProfanity();
        });

        static::updating(function ($listing) {
            // Set the authenticated user
            if (!$listing->user_id && Auth::check()) {
                $listing->user_id = Auth::id();
            }

            $listing->filterProfanity();
        });
    }

    /**
     * Scope to filter only recent and active listings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereNull('deleted_at')
            ->where('updated_at', '>=', now()->subDays(2))
            ->orderBy('updated_at', 'desc');
    }

    protected array $exceptionUsernames = ['poon', 'gol d', 'five pot'];

    /**
     * Filter profanity from the listing's attributes.
     */
    public function filterProfanity()
    {
        if (!$this->user_id && !in_array(strtolower($this->username), $this->exceptionUsernames)) {
            $this->username = Profanity::blocker($this->username)->filter();
        }
        
        $this->notes = Profanity::blocker($this->notes)->filter();
    }
}
