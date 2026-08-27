<?php

use App\Http\Controllers\api\CommentsController;
use App\Http\Controllers\api\EventsController;
use App\Http\Controllers\api\LikesController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// register user
Route::post('/register',[AuthController::class,'register'])->middleware('throttle:3,1');
// login user
Route::post('/login',[AuthController::class,'login'])->middleware('throttle:3,1');
// log out endpoint
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

// user management
Route::get('/user/{user}',[UserController::class,'oneUser']);
// update
Route::post('/users/update/{user}',[UserController::class,'update']);
// admin delete and update role
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
     // all users
    Route::get('/users',[UserController::class,'index']);
    Route::post('/users/delete/{user}', [UserController::class, 'softDelete']);
    Route::post('/users/create_admin/{user}', [UserController::class, 'makeAdmin']);
    Route::post('/users/demote_admin/{user}', [UserController::class, 'demoteAdmin']);
    //viewSuspended
    Route::get('/users/suspended', [UserController::class, 'viewSuspended']);
    // unsuspend user
     Route::post('/user/unsuspend/{user}', [UserController::class, 'unsuspend']);
    
});

// project mamagement 
Route::middleware(['auth:sanctum'])->group(function () {
// create project
Route::post('/project/create',[ProjectController::class,'create'])->name('create project');
// update project
Route::patch('/project/{project}/update',[ProjectController::class,'update'])->name('update project');
// all user projects
Route::get('/projects',[ProjectController::class,'index'])->name('all projects');
// soft delete project
Route::delete('/project/{project}/delete',[ProjectController::class,'destroy'])->name('delete project');
});

// like post


Route::middleware(['auth:sanctum'])->group(function () {
    // like a project
Route::post('project/{project}/like',[LikesController::class,'create']);
    // likes count
Route::get('/project/{project}/likes/count',[LikesController::class,'projectLikes']);
});

// comment maanagement
Route::middleware(['auth:sanctum'])->group(function () {
// create comment
Route::post('/project/{project}/comment',[CommentsController::class,'create'])->name('create comment');
// projectComments
Route::get('/project/{project}/comments',[CommentsController::class,'projectComments'])->name('project comments');
// update comment
Route::patch('/comment/{comment}/update',[CommentsController::class,'update'])->name('update comment');
// delete comment
Route::delete('/comment/{comment}/delete',[CommentsController::class,'destroy'])->name('delete comment');
});

// events management

Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {

// create event
Route::post('/event/create',[EventsController::class,'create'])->name('create event');
// update event
Route::patch('/event/{event}/update',[EventsController::class,'update'])->name('update event');
// delete event
Route::delete('/event/{event}/delete',[EventsController::class,'destroy'])->name('delete event');
});

// view upcoming events
Route::get('/events',[EventsController::class,'index']);