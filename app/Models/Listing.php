<?php

namespace App\Models;

use App\Events\ListingEvent;
use ConsoleTVs\Profanity\Facades\Profanity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    public $guarded = [];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            $listing->user_id = Auth::id();
            $listing->filterProfanity();
        });

        static::created(function ($listing) {
            $listing->handleEvent();
        });

        static::updating(function ($listing) {
            $listing->filterProfanity();
        });

        static::updated(function ($listing) {
            $listing->handleEvent();
        });

        static::deleted(function ($listing) {
            $listing->handleEvent();
        });
    }

    /**
     * Scope to filter only recent and active listings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereNull('sold_at')
            ->where('updated_at', '>=', now()->subDays(1))
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Scope to filter listings where updated_at is older than a day but younger than 2 days.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query
            ->whereNull('sold_at')
            ->whereBetween('updated_at', [now()->subDays(2), now()->subDay()])
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Filter profanity from the listing's attributes.
     */
    public function filterProfanity()
    {
        $this->notes = Profanity::blocker($this->notes)->filter();
    }

    /**
     * Handle the event after something happens to a listing.
     */
    public function handleEvent()
    {
        event(new ListingEvent($this));
        Cache::tags('home_listings')->flush();
    }

    protected $casts = [
        'sold_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
