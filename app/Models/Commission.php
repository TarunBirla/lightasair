<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'listing_type',
        'rate',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getRate($categoryId, $listingType)
    {
        $commission = self::where('is_active', true)
            ->where('listing_type', $listingType)
            ->where(function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId)
                      ->orWhereNull('category_id');
            })
            ->orderByRaw('category_id IS NULL') // Specific category first, then fallback
            ->first();

        return $commission ? $commission->rate : 10.00;
    }
}
