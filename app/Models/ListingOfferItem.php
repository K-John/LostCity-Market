<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ListingOfferItem extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $fillable = ['listing_offer_id', 'item_id', 'quantity'];

    public function listingOffer(): BelongsTo
    {
        return $this->belongsTo(ListingOffer::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
