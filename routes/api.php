<?php

use App\Http\Controllers\api\Aicontroller;
use App\Http\Controllers\api\CommentsController;
use App\Http\Controllers\api\EventsController;
use App\Http\Controllers\api\LikesController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\SettingsController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// register user
Route::post('/register',[AuthController::class,'register'])->middleware('throttle:3,1')
->name('register');
// login user
Route::post('/login',[AuthController::class,'login'])->middleware('throttle:3,1')
->name('login');
// log out endpoint
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum')
->name('logout');

// user management
Route::get('/user/{user}',[UserController::class,'oneUser'])->name('view single user');
// update
Route::post('/users/update/{user}',[UserController::class,'update'])->name('update user info');
// admin delete and update role
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
     // all users
    Route::get('/users',[UserController::class,'index'])->name('view users');
    Route::patch('/users/suspend/{user}', [UserController::class, 'softDelete'])->name('suspend user');
    Route::patch('/users/create_admin/{user}', [UserController::class, 'makeAdmin'])->name('make admin');
    Route::patch('/users/demote_admin/{user}', [UserController::class, 'demoteAdmin'])->name('demote admin');
    //viewSuspended
    Route::get('/users/suspended', [UserController::class, 'viewSuspended'])->name('suspended users');
    // unsuspend user
     Route::patch('/users/unsuspend/{user}', [UserController::class, 'unsuspend'])->name('unsuspend user');
    
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
Route::post('project/{project}/like',[LikesController::class,'create'])->name('like project');
    // likes count
Route::get('/project/{project}/likes/count',[LikesController::class,'projectLikes'])->name('project likes');
});

// comment management
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
Route::post('/event/{event}/update',[EventsController::class,'update'])->name('update event');
// delete event
Route::delete('/event/{event}/delete',[EventsController::class,'destroy'])->name('delete event');
});

// view upcoming events
Route::get('/events',[EventsController::class,'index'])->name('view events');

// settings controller

Route::middleware(['auth:sanctum'])->group(function () {
// updatePassword
Route::patch('/password/update',[SettingsController::class,'updatePassword'])
->name('update password');

// update username and email  updateUserInfo
Route::post('/username/update',[SettingsController::class,'updateUserInfo'])
->name('update name or email');

// delete account
Route::post('/deleteAccount',[SettingsController::class,'deleteAccount'])
->name('delete account');

});

// ai routes 
Route::post('/ai/chat',[AiController::class,'chat'])->name('ai chat');

// profile management
Route::middleware(['auth:sanctum'])->group(function () {
 //create profile
 Route::patch('/profile/update',[ProfileController::class,'update'])->name('update profile');
 // view profile
 Route::get('/user/{user}/profile',[ProfileController::class,'index'])->name('view profile');
});