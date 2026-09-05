<?php

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
use App\Models\User;
use App\Models\Comment;

// users create projects
test('users can comment on projects', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // create project
   $projects=Project::factory()->count(10)->create();
   //comment on project
   $response=$this->postJson('/api/project/2/comment',[
        'text'=>'dope'
   ]);
    
    $response->assertStatus(201);
});

// update comment
test('users can update comment on projects', function () {
    // create admin
    $user = User::factory()->create();
    // logged in user
    Sanctum::actingAs($user);

   //comment on project
   $comment=Comment::factory()->create([
        'user_id'=>$user->id,
        'text'=>'cool'
   ]);
   // update comment
    $response=$this->patchJson('/api/comment/1/update',[
        'text'=>'dope'
   ]);
    
    $response->assertStatus(200);
});

// users can delete their comments
test('users can delete comments on projects', function () {
    // create admin
    $user = User::factory()->create();
    // logged in user
    Sanctum::actingAs($user);

   //comment on project
   $comment=Comment::factory()->create([
        'user_id'=>$user->id,
        'text'=>'cool'
   ]);
   // update comment
    $response=$this->deleteJson('/api/comment/1/delete');
    
    $response->assertStatus(200);
});

// project comments
test('users can view project comments', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // create project
   $projects=Project::factory()->create();

   //comment on project
   $comments=Comment::factory()->create([
        'project_id'=>$projects->id,
   ]);
   $response=$this->getJson('/api/project/2/comments');
    
    $response->assertStatus(200);
});