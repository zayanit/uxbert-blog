<?php

use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('posts', [PostsController::class, 'index'])->name('allPosts');

Route::group(['middleware' => 'auth'], function () {
    Route::get('posts/create', [PostsController::class, 'create']);
    Route::get('posts/{post}', [PostsController::class, 'show'])->name('showPost');
    Route::get('posts/{post}/edit', [PostsController::class, 'edit'])->name('editPost');

    Route::post('posts', [PostsController::class, 'store'])->name('storePost');
    Route::patch('posts/{post}', [PostsController::class, 'update'])->name('updatePost');
});


Auth::routes();
