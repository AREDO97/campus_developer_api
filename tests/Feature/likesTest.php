<?php

use App\Models\Like;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
use App\Models\User;

// users create projects
test('users can like projects', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // create project
    $project = Project::factory()->create();
    // already liked
    $liked=Like::where('user_id',$user->id)
    ->where('project_id',$project->id)->first();
    if($liked){
        $liked->delete();
    }
    //like
    $response = $this->postJson('/api/project/1/like',[
        'user_id'=>$user->id,
        'project_id'=>$project->id,
        
    ]);

    $response->assertStatus(201);
});

// project likes
test(' project lies', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // create project
    $project = Project::factory()->create();
   
    //like
    $response = $this->getJson('/api/project/1/likes/count');

    $response->assertStatus(200);
});