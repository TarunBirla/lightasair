<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'short_description',
        'condition',
        'listing_type',
        'price',
        'rental_price_day',
        'rental_price_week',
        'deposit_amount',
        'reserve_price',
        'quantity',
        'sku',
        'brand',
        'model_number',
        'year_manufactured',
        'images',
        'offers_shipping',
        'offers_collection',
        'location',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'view_count',
        'is_featured',
    ];

    protected $casts = [
        'images'            => 'array',
        'offers_shipping'   => 'boolean',
        'offers_collection' => 'boolean',
        'is_featured'       => 'boolean',
        'approved_at'       => 'datetime',
        'price'             => 'decimal:2',
        'rental_price_day'  => 'decimal:2',
        'rental_price_week' => 'decimal:2',
        'deposit_amount'    => 'decimal:2',
        'reserve_price'     => 'decimal:2',
    ];

    // ─── Auto-generate slug ───────────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug  = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // ─── Status helpers ───────────────────────────────────────────────────────
    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }
    public function isDraft(): bool     { return $this->status === 'draft'; }
    public function isSold(): bool      { return $this->status === 'sold'; }

    // ─── Listing-type helpers ─────────────────────────────────────────────────
    public function isForSale(): bool    { return $this->listing_type === 'sell'; }
    public function isForRent(): bool    { return $this->listing_type === 'rent'; }
    public function isForAuction(): bool { return $this->listing_type === 'auction'; }

    // ─── Image helpers ────────────────────────────────────────────────────────
    public function primaryImage(): ?string
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true) ?: [];
        }
        return is_array($images) ? ($images[0] ?? null) : null;
    }

    public function primaryImageUrl(): string
    {
        $img = $this->primaryImage();
        if (!$img) {
            return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="300" height="200" viewBox="0 0 300 200"><rect width="300" height="200" fill="%23f5f4ef"/><path d="M100 130l25-30 20 25 35-45 40 50H100z" fill="%23e5e4df"/><circle cx="130" cy="80" r="14" fill="%23e5e4df"/><text x="150" y="165" font-family="sans-serif" font-size="13" font-weight="bold" fill="%23888888" text-anchor="middle">No Product Image</text></svg>';
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

    // ─── Relationships ────────────────────────────────────────────────────────
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForSale($query)
    {
        return $query->where('listing_type', 'sell');
    }

    public function scopeForRent($query)
    {
        return $query->where('listing_type', 'rent');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
