<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    protected $fillable = [
        'booking_id',
        'item_id',
        'qty',
        'price_per_day',
        'total_days',
        'total_amount'
    ];

    public function item()
    {
        return $this->belongsTo(
            Item::class
        );
    }
}