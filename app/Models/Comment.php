<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    // allowed
         use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'text'
    ];
    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
