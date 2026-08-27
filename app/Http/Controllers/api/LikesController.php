<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    // like a post
    public function create(Project $project)
    {
        $user=auth()->user();
        // like toggle
         $liked=Like::where('user_id',$user->id)
       ->where('project_id',$project->id)->first();
       if(!$liked){

         $like=Like::create([
            'user_id'=>$user->id,
            'project_id'=>$project->id
        ]);
          return response()->json([
            'user'=>$user,
            'project'=>$project
          ]);

       }
       
         $liked->delete();

        // return response
    }
    // project likes
    public function projectLikes(Project $project)
    {
        $projectLikesCount=$project->likes->count();
        $projectLikes=Project::with('likes.user')->findOrFail($project->id);
       
        return response()->json([
            'likes_count'=>$projectLikesCount,
            'user'=>$projectLikes
        ]);
    }

}
