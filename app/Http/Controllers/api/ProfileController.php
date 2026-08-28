<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
            // create profile
    public function update(Request $request)
    {
        $user=auth()->user();
        $profile=$user->profile;
        $request->validate([
          //  'profile_image'=>'image',
            'course'=>'string'
        ]);
        // check 
        if($user->id !== $profile->user_id){
            abort(403,'Unauthorised');
        }
        // image path
        $path=null;
        if($request->hasFile('profile_image')){
            $path=$request->file('profile_image')->store('images','public');
        }
        $profile->update([
            'user_id'=>$user->id,
            'course'=>$request->course ?? $profile->course,
            'profile_image'=>$path ?? $profile->profile_image,
            'year_of_study'=>$request->year_of_study ?? $profile->year_of_study,
            'hobbies'=>$request->hobbies ?? $profile->hobbies,
            'phone'=>$request->phone ?? $profile->phone
        ]);
        // response
        return response()->json([
            'message'=>'Profile updated successifully',
            'profile'=>$profile
        ]);
    }
    // view user profile
    public function index(User $user)
    {
        return response($user->profile);
    }
}
