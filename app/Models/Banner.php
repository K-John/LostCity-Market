<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Banner extends Model
{
    use HasFactory;

    public $guarded = [];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($listing) {
            $listing->handleEvent();
        });

        static::updated(function ($listing) {
            $listing->handleEvent();
        });

        static::deleted(function ($listing) {
            $listing->handleEvent();
        });
    }

    /**
     * Handle the event after something happens to a listing.
     */
    public function handleEvent()
    {
        Cache::forget("banners.global.active");
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', now());
            })->where(function ($query) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            });
    }

    public function scopeGlobal($query)
    {
        return $query->whereDoesntHave('items');
    }
}
