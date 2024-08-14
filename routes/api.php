<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\AuthOrAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(AuthOrAdmin::class);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware(AuthOrAdmin::class);
    Route::get('/profile', [AuthController::class, 'profile'])->middleware(AuthOrAdmin::class);
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->middleware(AuthOrAdmin::class);
});

Route::group([
    'prefix' => 'user'
], function () {
    Route::get('/all-user', [UserController::class, 'index'])->middleware(Admin::class);
    Route::post('/create-user', [UserController::class, 'store'])->middleware(Admin::class);
    Route::put('/update-user/{user}', [UserController::class, 'update'])->middleware(Admin::class);
    Route::delete('/delete-user/{user}', [UserController::class, 'destroy'])->middleware(Admin::class);
});

Route::group([
    'prefix' => 'admin'
], function () {
    Route::get('/all-admin', [AdminController::class, 'index'])->middleware(Admin::class);
    Route::post('/create-admin', [AdminController::class, 'store'])->middleware(Admin::class);
    Route::put('/update-admin/{admin}', [AdminController::class, 'update'])->middleware(Admin::class);
    Route::delete('/delete-admin/{admin}', [AdminController::class, 'destroy'])->middleware(Admin::class);
});

Route::group([
    'prefix' => 'post'
], function () {
    Route::get('/all-post', [PostController::class, 'index'])->middleware(AuthOrAdmin::class);
    Route::post('/create-post', [PostController::class, 'store'])->middleware(Admin::class);
    Route::post('/update-post/{post}', [PostController::class, 'update'])->middleware(Admin::class);
    Route::delete('/delete-post/{post}', [PostController::class, 'destroy'])->middleware(Admin::class);
    Route::get('show-file/{media}',[PostController::class, 'show'])->middleware(AuthOrAdmin::class);
});

// Route::Group([
//     'middleware' => ['auth:api']
// ], function () {

//     Route::apiResource('comments', CommentController::class, [
//         'except' => 'store'
//     ]);
// });
