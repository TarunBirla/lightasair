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
    'status'
];

protected $casts = [
    'image' => 'array'
];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}