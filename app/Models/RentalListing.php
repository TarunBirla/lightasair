<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RentalListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'product_id',
        'title', 'slug', 'description', 'short_description', 'images',
        'brand', 'model_number', 'year_manufactured', 'condition',
        'price_per_day', 'price_per_week', 'deposit_amount',
        'total_qty', 'available_qty',
        'location', 'offers_delivery', 'delivery_fee',
        'min_rental_days', 'max_rental_days',
        'status', 'rejection_reason', 'approved_at',
        'view_count', 'is_featured',
    ];

    protected $casts = [
        'images'          => 'array',
        'offers_delivery' => 'boolean',
        'is_featured'     => 'boolean',
        'approved_at'     => 'datetime',
        'price_per_day'   => 'decimal:2',
        'price_per_week'  => 'decimal:2',
        'deposit_amount'  => 'decimal:2',
        'delivery_fee'    => 'decimal:2',
    ];

    // ─── Auto slug ────────────────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (RentalListing $listing) {
            if (empty($listing->slug)) {
                $base  = Str::slug($listing->title);
                $count = static::where('slug', 'LIKE', "{$base}%")->count();
                $listing->slug = $count ? "{$base}-{$count}" : $base;
            }
        });
    }

    // ─── Status helpers ───────────────────────────────────────────────────────
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
    public function isDraft(): bool    { return $this->status === 'draft'; }

    // ─── Image helpers ────────────────────────────────────────────────────────
    public function primaryImageUrl(): string
    {
        $img = $this->images[0] ?? null;
        return $img ? asset('storage/' . $img) : asset('images/placeholder.png');
    }

    // ─── Availability check ───────────────────────────────────────────────────
    /**
     * Returns true if this listing has at least $qty units free for every day
     * in [startDate, endDate] (inclusive).
     */
    public function isAvailable(string $startDate, string $endDate, int $qty = 1): bool
    {
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);

        // Check blocked dates
        $blocked = $this->blockedDates()
                        ->whereBetween('blocked_date', [$start, $end])
                        ->exists();
        if ($blocked) {
            return false;
        }

        // Count confirmed/active bookings that overlap the requested window
        $booked = $this->rentalBookings()
                       ->whereIn('status', ['confirmed', 'active'])
                       ->where(function ($q) use ($start, $end) {
                           $q->whereBetween('start_date', [$start, $end])
                             ->orWhereBetween('end_date', [$start, $end])
                             ->orWhere(function ($q2) use ($start, $end) {
                                 $q2->where('start_date', '<=', $start)
                                    ->where('end_date', '>=', $end);
                             });
                       })
                       ->sum('qty');

        return ($this->total_qty - $booked) >= $qty;
    }

    /**
     * Returns all booked date ranges as array of [start, end] strings.
     * Used to feed the front-end availability calendar.
     */
    public function bookedRanges(): array
    {
        return $this->rentalBookings()
                    ->whereIn('status', ['confirmed', 'active'])
                    ->get(['start_date', 'end_date'])
                    ->map(fn($b) => [
                        'start' => $b->start_date,
                        'end'   => $b->end_date,
                    ])
                    ->toArray();
    }

    /**
     * Returns all manually blocked dates as array of date strings.
     */
    public function blockedDatesList(): array
    {
        return $this->blockedDates()
                    ->pluck('blocked_date')
                    ->map(fn($d) => $d instanceof Carbon ? $d->toDateString() : (string) $d)
                    ->toArray();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────
    public function scopeApproved($query) { return $query->where('status', 'approved'); }
    public function scopePending($query)  { return $query->where('status', 'pending'); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }

    // ─── Relationships ────────────────────────────────────────────────────────
    public function vendor()        { return $this->belongsTo(User::class, 'user_id'); }
    public function category()      { return $this->belongsTo(Category::class); }
    public function product()       { return $this->belongsTo(Product::class); }
    public function blockedDates()  { return $this->hasMany(RentalBlockedDate::class); }
    public function rentalBookings(){ return $this->hasMany(RentalBooking::class); }
}
