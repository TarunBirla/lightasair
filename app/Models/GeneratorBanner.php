<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratorBanner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'item_id',
        'status'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}