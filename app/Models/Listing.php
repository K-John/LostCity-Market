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
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Listing extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public $guarded = [];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            $listing->ip = request()->ip();
            $listing->user_id = Auth::id();
            $listing->filterProfanity();

            // Prevent user from abusing the bump feature by deleting and recreating listings
            $deletedListing = Listing::withTrashed()
                ->where('user_id', $listing->user_id)
                ->where('item_id', $listing->item_id)
                ->where('type', $listing->type)
                ->whereNotNull('deleted_at')
                ->where('updated_at', '>=', now()->subMinutes(30))
                ->first();

            if ($deletedListing) {
                $listing->updated_at = $deletedListing->updated_at;
            }
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
            ->whereNull('paused_at')
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
            ->whereBetween('updated_at', [now()->subDays(7), now()->subDay()])
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Scope to filter listings that are paused.
     */
    public function scopePaused(Builder $query): Builder
    {
        return $query
            ->whereNotNull('paused_at')
            ->where('updated_at', '>=', now()->subDays(1))
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
        Cache::forget("item_{$this->item->id}_listings_{$this->type}");

        // If the listing was sold, flush the item's sold listings cache
        if ($this->sold_at) {
            Cache::forget("item_{$this->item->id}_sold_listings");
        }
    }

    protected $casts = [
        'sold_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
        'paused_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['id', 'item_id', 'type', 'price', 'quantity', 'notes', 'username', 'updated_at', 'deleted_at', 'sold_at', 'paused_at'])
            ->logOnlyDirty();
    }
}
