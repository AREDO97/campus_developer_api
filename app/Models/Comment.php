<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // allowed
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
