<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Comment;

class CommentsController extends Controller
{
    // create comment
    public function create(Project $project,Request $request)
    {
        // validate comment
        $user=auth()->user();
        $request->validate([
            'text'=>'required|max:100'
        ]);
        $comment=Comment::create([
            'user_id'=>$user->id,
            'project_id'=>$project->id,
            'text'=>$request->text
        ]);
        // response
        return response()->json([
            'comment'=>$comment->text,
            'user'=>$user,
            'project'=>$project
        ]);
    }
    // project comments
    public function projectComments(Project $project)
    {
        $projectComments=Project::with('comments.user')->findOrFail($project->id);
        // response
        return response()->json([
            'project comments'=>$projectComments
        ]);
    }
    // update comment
    public function update(Comment $comment,Request $request)
    {
        $request->validate([
            'text'=>'max:100'
        ]);
        // users
        $user=auth()->user();
        if($comment->user->id !== $user->id){
            abort(403,'Unauthorised action');
        }
        // update comment
        $comment->update([
            'text'=>$request->text
        ]);
        // response 
        return response()->json([
            'message'=>'Comment update',
            'comment'=>$comment,
            'project'=>$comment->project,
        ]);
    }
    // delete comment
    public function destroy(Comment $comment)
    {
        $user=auth()->user();
        if($comment->user->id !== $user->id){
            abort(403,'Unauthorised action');
        }
        // delete
        $comment->delete();
        return response()->json([
            'message'=>'Comment deleted'
        ]);
    }
    
}
