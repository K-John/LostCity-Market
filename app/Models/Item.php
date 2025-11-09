<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use HasFactory, LogsActivity;

    public $guarded = [];

    /**
     * Scope to filter only recent and active listings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function banners(): BelongsToMany
    {
        return $this->belongsToMany(Banner::class)
            ->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['id', 'name', 'cost', 'slug', 'is_active'])
            ->logOnlyDirty();
    }
}
