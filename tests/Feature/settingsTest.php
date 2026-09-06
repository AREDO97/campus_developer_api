<?php

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
use App\Models\User;

// users create projects
test('users can update their info', function () {
    // create admin
    $user = User::factory()->create();
    // logged in user
    Sanctum::actingAs($user);
    // update info
    $response = $this->postJson('/api/username/update',[
        'name'=>'ivan',
       
    ]);

    $response->assertStatus(200);
});

// users can delete their accounts

test('users can delete their accounts', function () {
    // create admin
    $user = User::factory()->create();
    // logged in user
    Sanctum::actingAs($user);
    // update info
    $response = $this->postJson('/api/deleteAccount',[
        'password'=>'ivan256@@'
    ]);

    $response->assertStatus(200);
});