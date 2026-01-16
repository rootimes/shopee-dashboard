<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $guarded = [];

    protected $casts = [
        'incurred_time' => 'datetime',
    ];
}
