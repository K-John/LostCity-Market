<?php

namespace App\Models;

use ConsoleTVs\Profanity\Facades\Profanity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ListingOffer extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = ['title'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offer) {
            $offer->filterProfanity();
        });

        static::updating(function ($offer) {
            $offer->filterProfanity();
        });
    }

    public function filterProfanity(): void
    {
        $this->title = Profanity::blocker($this->title)->filter();
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ListingOfferItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
