<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // allowed
    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
        'date',
        'status'
    ];
}
