<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = ['name', 'location', 'description', 'price', 'duration', 'difficulty', 'capacity', 'available_dates', 'image_url', 'is_available'];

    protected $casts = ['price' => 'decimal:2', 'available_dates' => 'array', 'is_available' => 'boolean'];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
