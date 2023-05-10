<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostAdminController;
use App\Http\Controllers\PostHistoryController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\PostSavedController;

// Main Routes
Route::get('/', [PostController::class, 'index']);
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');

// Auth Routes
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/postlogin', [AuthController::class, 'login'])->name('postlogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ADMIN DASHBOARD
Route::middleware(['auth'])->prefix('dashboard')->group(function() {
    // DASHBOARD
    Route::get('', function(){
        return view('dashboard.index');
    });

    // POSTS
    Route::resource('posts', PostAdminController::class, ['except' => 'show']);
    Route::get('posts/{id}/show', [PostAdminController::class, 'show']);

    // SAVED POSTS
    Route::get('/posts-saved', [PostSavedController::class, 'index'])->name('posts.saved');
    Route::resource('posts-saved', PostSavedController::class, ['except' => ['index', 'create', 'show']]);

    // UPLOAD IMAGE (THROUGH THE QUILL) ROUTE
    Route::post('/image-upload-post', [PostImageController::class, 'store'])->name('image.store');

    // COMMENTS
    Route::resource('comments', CommentController::class, ['except' => 'store']);

    // USERS
    Route::resource('users', UserController::class);

    // ROLES
    Route::resource('roles', RoleController::class);

    // HISTORY POSTS
    Route::get('posts/{id}/edit/history', [PostHistoryController::class, 'index'])->name('history.index');
    Route::get('posts/history/{history}', [PostHistoryController::class, 'show'])->name('history.show');
    Route::get('posts/history/{post}/{history}/revert', [PostHistoryController::class, 'revert'])->name('history.revert');
});

// Store Comment Route
Route::post('/comment/store', [CommentController::class, 'store'])->name('comments.store');

// Send Mail Route
Route::group(['middleware' => ['auth']], function() {
    Route::get('/send', [MailController::class, 'index'])->name('mail.send');
});

// Profile Route
Route::get('/profile', function(){
    return view('profile');
});
