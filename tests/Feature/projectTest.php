<?php

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
use App\Models\User;

// users create projects
test('users can create projects', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // register user
    $response = User::factory()->count(10)->create([
        'status' => 'active',
    ]);
    $response = $this->postJson('/api/project/create',[
        'user_id'=>User::factory()->create(),
        'title'=>'cool',
        'description'=>'laravel',
        'url'=>'https://linux.com'
    ]);

    $response->assertStatus(201);
});

// users update their own projects
test('users can update their own projects', function () {
    // create admin
    $user = User::factory()->create();
    // logged in user
    Sanctum::actingAs($user);
    // create project
    $projects=Project::factory()->create([
        'user_id'=>$user->id,
        'title'=>'cool',
        'description'=>'laravel',
        'url'=>'https://linux.com'
    ]);
    $response = $this->patchJson('/api/project/1/update',[
       // 'user_id'=>$user->id,
        'title'=>'cool',
        'description'=>'routing',
        'url'=>'https://laravel.com'
    ]);

    $response->assertStatus(200);
});

// users delete their own projects
test('users can delete their own projects', function () {
    // create admin
    $user = User::factory()->create();
    // logged in user
    Sanctum::actingAs($user);
    // create project
    $projects=Project::factory()->create([
        'user_id'=>$user->id,
        'title'=>'cool',
        'description'=>'laravel',
        'url'=>'https://linux.com'
    ]);
    $response = $this->deleteJson('/api/project/1/delete',[
       // 'user_id'=>$user->id,
       'status'=>'deleted'
    ]);

    $response->assertStatus(200);
});

// users can view all projects
test('users can view projects', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    // register user
    $response = Project::factory()->count(10)->create([
        'status' => 'active',
    ]);
    $response = $this->getJson('/api/projects');

    $response->assertStatus(200);
});
