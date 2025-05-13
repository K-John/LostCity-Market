<?php

namespace App\Models;

use ConsoleTVs\Profanity\Facades\Profanity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    public $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referral) {
            $referral->code = Profanity::blocker($referral->code)->filter();
        });
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
