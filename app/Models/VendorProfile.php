<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'bio',
        'logo',
        'website',
        'address',
        'city',
        'postcode',
        'country',
        'vat_number',
        'bank_account',
        'commission_rate',
        'approval_status',
        'rejection_reason',
        'total_listings',
        'total_sales',
        'total_revenue',
        'average_rating',
        'rating_count',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_revenue'   => 'decimal:2',
        'average_rating'  => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function isSuspended(): bool
    {
        return $this->approval_status === 'suspended';
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-vendor.png');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }
}
