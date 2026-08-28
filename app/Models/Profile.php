<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class Profile extends Model
{
    //
    protected $fillable = [
        'profile_image',
        'user_id',
        'course',
        'year_of_study',
        'hobbies',
        'phone'
    ];
// array 
    #[Override]
    protected function casts() : array
    {
        return [
            'hobbies'=>'array'
        ];
    }
    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
}
