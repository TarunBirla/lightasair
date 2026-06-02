<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLead extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'item_id',
        'item_name',
        'name',
        'email',
        'phone',
        'message'
    ];
}