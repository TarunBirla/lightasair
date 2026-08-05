<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'image',
        'qty',
        'available_qty',
        'price_per_day',
        'selling_price',
        'rental_price',
        'is_sell',
        'is_rental',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'image' => 'array',
        'is_sell' => 'boolean',
        'is_rental' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}