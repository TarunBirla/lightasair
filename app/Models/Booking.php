<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_no',
        'user_id',
        'total_amount',
        'start_date',
        'end_date',
        'total_days',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(
            BookingItem::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
    
}