<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // create project
    public function create(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'max:200',
            'url'=>'url'
        ]);
        // create 
        $project=Project::create([
            'user_id'=>auth()->id(),
            'title'=>$request->title,
            'description'=>$request->description,
            'url'=>$request->url
        ]);
        // response
        return response()->json([
            'message'=>'Project created successifully',
            'project'=>$project
        ]);
    }

    // update project
    public function update(Request $request,Project $project)
    {
        $user=auth()->user();
        if($user->id !==$project->user_id){
            abort(403,'Unauthorised');
        }
         $request->validate([
            'title'=>'max:100',
            'description'=>'max:200',
            'url'=>'url'
        ]);

        $project->update([
            'title'=>$request->title ?? $project->title,
            'description'=>$request->description ?? $project->description,
            'url'=>$request->url ?? $project->url
        ]);

         // response
        return response()->json([
            'message'=>'Project updated successifully',
            'project'=>$project
        ]);
    }
    // all projects
    public function index()
    {
        $projects=Project::latest()->paginate(10);
        return response()->json($projects);
    }
    // delete project
    public function destroy(Project $project)
    {
         $user=auth()->user();
        if($user->id !==$project->user_id && $user->role !=='admin' && $user->role !=='super_admin'){
            abort(403,'Unauthorised');
        }

        $project->update([
            'status'=>'deleted'
        ]);
        // response
        return response()->json([
            'message'=>'Projected deleted successifully',
            'project'=>$project
        ]);
    }
}
