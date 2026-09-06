<?php

use App\Models\Event;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
use App\Models\User;
use Illuminate\Http\UploadedFile;


// users create projects
test('admins can create events', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
  
    $response = $this->postJson('/api/event/create',[
        'user_id'=>User::factory()->create(),
        'title'=>'cool',
        'description'=>'laravel',
        'image'=>UploadedFile::fake()->image('ivan.jpg')
    ]);

    $response->assertStatus(201);
});

// update events
test('admins can update events', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    $events=Event::factory()->count(10)->create();
    $response = $this->postJson('/api/event/2/update',[
        //'user_id'=>User::factory()->create(),
        'title'=>'cool',
        'description'=>'laravel',
        'image'=>UploadedFile::fake()->image('ivan.jpg')
    ]);

    $response->assertStatus(200);
});
// admins can delete events
test('admins can delete events', function () {
    // create admin
    $user = User::factory()->create([
        'role' => 'admin',
    ]);
    // logged in user
    Sanctum::actingAs($user);
    $events=Event::factory()->count(10)->create();
    $response = $this->deleteJson('/api/event/2/delete',[
        'status'=>'deleted',
    ]);
  
    $response->assertStatus(200);
});

// view all events
test('users can view events', function () {
    // create admin
    $user = User::factory()->create();
    // logged in user
   // Sanctum::actingAs($user);
    $events=Event::factory()->count(10)->create();
    $response = $this->getJson('/api/events');
  
    $response->assertStatus(200);
});