<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    //
         use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id'
    ];
    // user like
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // project like
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
