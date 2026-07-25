<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_ref', 'rental_listing_id', 'customer_id', 'vendor_id',
        'start_date', 'end_date', 'total_days',
        'price_per_day', 'deposit_amount', 'delivery_fee',
        'subtotal', 'total_amount', 'qty',
        'requires_delivery', 'delivery_address',
        'status', 'deposit_status', 'deposit_refunded_at',
        'payment_method', 'payment_reference', 'payment_status',
        'customer_notes', 'vendor_notes', 'cancellation_reason',
    ];

    protected $casts = [
        'start_date'          => 'date',
        'end_date'            => 'date',
        'deposit_refunded_at' => 'datetime',
        'requires_delivery'   => 'boolean',
        'price_per_day'       => 'decimal:2',
        'deposit_amount'      => 'decimal:2',
        'delivery_fee'        => 'decimal:2',
        'subtotal'            => 'decimal:2',
        'total_amount'        => 'decimal:2',
    ];

    // ─── Status helpers ───────────────────────────────────────────────────────
    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isConfirmed(): bool  { return $this->status === 'confirmed'; }
    public function isActive(): bool     { return $this->status === 'active'; }
    public function isReturned(): bool   { return $this->status === 'returned'; }
    public function isCompleted(): bool  { return $this->status === 'completed'; }
    public function isCancelled(): bool  { return $this->status === 'cancelled'; }

    // ─── Relationships ────────────────────────────────────────────────────────
    public function listing()  { return $this->belongsTo(RentalListing::class, 'rental_listing_id'); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function vendor()   { return $this->belongsTo(User::class, 'vendor_id'); }

    // ─── Scopes ───────────────────────────────────────────────────────────────
    public function scopePending($q)   { return $q->where('status', 'pending'); }
    public function scopeActive($q)    { return $q->where('status', 'active'); }
    public function scopeCompleted($q) { return $q->where('status', 'completed'); }

    // ─── Booking ref generator ────────────────────────────────────────────────
    public static function generateRef(): string
    {
        do {
            $ref = 'RB-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        } while (static::where('booking_ref', $ref)->exists());

        return $ref;
    }
}
