<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalBlockedDate extends Model
{
    protected $fillable = ['rental_listing_id', 'blocked_date', 'reason'];

    protected $casts = ['blocked_date' => 'date'];

    public function listing()
    {
        return $this->belongsTo(RentalListing::class);
    }
}
