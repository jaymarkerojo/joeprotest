<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = ['reference_no', 'package_id', 'user_id', 'transport_id', 'contact_number', 'tour_date', 'guest_count', 'special_requests', 'total_amount', 'status'];

    protected $casts = ['tour_date' => 'date', 'total_amount' => 'decimal:2'];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
