<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Television extends Model
{
    protected $fillable = [
        'title',
        'tag',
        'description',
        'image'
    ];
}