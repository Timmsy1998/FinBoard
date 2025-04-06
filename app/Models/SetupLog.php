<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetupLog extends Model
{
    protected $fillable = ['event', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];
}
