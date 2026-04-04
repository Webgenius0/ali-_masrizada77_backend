<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'type',
        'category',
        'title',
        'url',
        'status',
        'order',
    ];
}
