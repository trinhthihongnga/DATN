<?php

use App\Http\Controllers\Api\BackEnd\Admin\AccountController;
use App\Http\Controllers\Api\BackEnd\Admin\CategoryController;
use App\Http\Controllers\Api\BackEnd\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\BackEnd\CommentController;
use App\Http\Controllers\Api\BackEnd\Creator\PostController;
use App\Http\Controllers\Api\BackEnd\HomeController;
use App\Http\Controllers\Api\BackEnd\ProfileController;
use App\Http\Controllers\Api\FrontEnd\CreatorController;
use App\Http\Controllers\Api\FrontEnd\PostController as HomePostController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Get comment
Route::middleware('is_approved')->get('/get-comments', [CommentController::class, 'getComments'])->name('get-comments');
Route::middleware('is_approved')->get('/get-post/{slugPost}', [HomeController::class, 'getPost'])->name('post.get-post');

// Infor creator
Route::get('/get-infor-user/{id}', [CreatorController::class, 'getInfor'])->name('cretor.get-infor');

Route::Get('/get-new-post-interested', [HomePostController::class, 'getPostInterested'])->name('post.get-post-interested');

// creator
Route::middleware(['is_approved', 'auth:accounts', 'is_creator'])->prefix('creator')->name('creator.')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::post('/post-valition', [PostController::class, 'validateStore'])->name('posts.validate');
    Route::post('/posts/{id}', [PostController::class, 'update'])
        ->name('posts.update');
    
    Route::get('/get-count-view/{id}', [PostController::class, 'getViewCount'])->name('posts.get-count-view');
});

Route::middleware([
    'is_approved',
    'auth:accounts'
])
->prefix('user')->group(function () {
    Route::apiResource('profiles', ProfileController::class);
    Route::post('/profiles/{id}', [ProfileController::class, 'update'])->name('profiles.update');

    // create comment
    Route::post('/comment/{id}', [CommentController::class, 'createComment'])->name('create-comment');
});


// admin
Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/get-chart-account', [DashboardController::class, 'getChartAccount'])->name('get-chart-account');
    Route::get('/get-chart-post', [DashboardController::class, 'getChartPost'])->name('get-chart-post');
    Route::get('/get-chart-category', [DashboardController::class, 'getChartCategory'])->name('get-chart-category');

    Route::apiResource('accounts', AccountController::class);
    Route::get('/change-to-creator/{id}', [AccountController::class, 'changeToCreator'])->name('accounts.change-to-creator');
    Route::get('/change-to-reader/{id}', [AccountController::class, 'changeToReader'])->name('accounts.change-to-reader');
    Route::post('/change-status-account/{id}', [AccountController::class, 'changeStatus'])->name('accounts.change-status');
    Route::get('/get-status-account/{id}', [AccountController::class, 'getStatus'])->name('accounts.get-status');
    Route::post('/delete-selected-accounts', [AccountController::class, 'deleteAccounts'])->name('accounts.delete-accounts');

    Route::apiResource('categories', CategoryController::class);
    Route::post('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('/change-status-category', [CategoryController::class, 'changeStatus'])->name('categories.change-status');

    Route::apiResource('/posts', AdminPostController::class);
    Route::post('/posts/{id}', [AdminPostController::class, 'update'])->name('posts.update');
    Route::get('/change-approved-post/{id}', [AdminPostController::class, 'changeApproved'])->name('posts.change-approved');
    Route::get('/change-status-post/{id}', [AdminPostController::class, 'changestatus'])->name('posts.change-status');
    Route::post('/delete-select-posts', [AdminPostController::class, 'deletePosts'])->name('posts.delete-posts');
});