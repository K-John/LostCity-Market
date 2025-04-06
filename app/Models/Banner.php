<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function scopeActive($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('start_at')
                ->orWhere('start_at', '<=', now());
        })->where(function ($query) {
            $query->whereNull('end_at')
                ->orWhere('end_at', '>=', now());
        });
    }
}
