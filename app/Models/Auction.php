<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'product_id',
        'title',
        'slug',
        'description',
        'short_description',
        'condition',
        'images',
        'location',
        'starting_bid',
        'reserve_price',
        'current_bid',
        'min_increment',
        'bid_count',
        'start_time',
        'end_time',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'winner_id',
        'winning_bid',
        'payment_status',
        'view_count',
        'is_featured',
    ];

    protected $casts = [
        'images'        => 'array',
        'starting_bid'  => 'decimal:2',
        'reserve_price' => 'decimal:2',
        'current_bid'   => 'decimal:2',
        'min_increment' => 'decimal:2',
        'winning_bid'   => 'decimal:2',
        'start_time'    => 'datetime',
        'end_time'      => 'datetime',
        'approved_at'   => 'datetime',
        'is_featured'   => 'boolean',
    ];

    // Auto-generate slug
    protected static function booted(): void
    {
        static::creating(function (Auction $auction) {
            if (empty($auction->slug)) {
                $base  = Str::slug($auction->title);
                $count = static::where('slug', 'LIKE', "{$base}%")->count();
                $auction->slug = $count ? "{$base}-{$count}" : $base;
            }
            if ($auction->current_bid <= 0 && $auction->starting_bid > 0) {
                $auction->current_bid = $auction->starting_bid;
            }
        });
    }

    // Status Helpers
    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isActive(): bool    { return $this->status === 'active' && $this->end_time && $this->end_time->isFuture(); }
    public function isEnded(): bool     { return $this->status === 'ended' || ($this->end_time && $this->end_time->isPast()); }
    public function isRejected(): bool  { return $this->status === 'rejected'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function reserveMet(): bool
    {
        return $this->current_bid >= $this->reserve_price;
    }

    public function nextMinBid(): float
    {
        if ($this->bid_count == 0) {
            return (float) $this->starting_bid;
        }
        return (float) ($this->current_bid + $this->min_increment);
    }

    public function primaryImageUrl(): string
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true) ?: [];
        }
        $img = is_array($images) ? ($images[0] ?? null) : null;
        if (!$img) {
            return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect width="300" height="200" fill="%23f5f4ef"/><path d="M100 130l25-30 20 25 35-45 40 50H100z" fill="%23e5e4df"/><circle cx="130" cy="80" r="14" fill="%23e5e4df"/><text x="150" y="165" font-family="sans-serif" font-size="13" font-weight="bold" fill="%23888888" text-anchor="middle">No Auction Image</text></svg>';
        }
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }
        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }
        if (str_starts_with($img, '/storage/')) {
            return asset(ltrim($img, '/'));
        }
        return asset('storage/' . $img);
    }

    public function allImageUrls(): array
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true) ?: [];
        }
        if (!is_array($images) || empty($images)) {
            return [$this->primaryImageUrl()];
        }
        return array_map(function($img) {
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) return $img;
            if (str_starts_with($img, 'storage/')) return asset($img);
            if (str_starts_with($img, '/storage/')) return asset(ltrim($img, '/'));
            return asset('storage/' . $img);
        }, $images);
    }

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function bids()
    {
        return $this->hasMany(AuctionBid::class)->latest();
    }

    public function highestBid()
    {
        return $this->hasOne(AuctionBid::class)->latestOfMany('amount');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('end_time', '>', now());
    }

    public function scopeEnded($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'ended')->orWhere('end_time', '<=', now());
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
