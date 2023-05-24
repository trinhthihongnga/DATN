<?php

use App\Http\Controllers\BackEnd\Auth\ForgotPasswordController;
use App\Http\Controllers\BackEnd\Auth\LoginController;
use App\Http\Controllers\BackEnd\Auth\ResgisterController;
use App\Http\Controllers\FrontEnd\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\FrontEnd\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\FrontEnd\Creator\CreatorController;
use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\FrontEnd\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('login', [LoginController::class, 'login'])->name('guest.login');
Route::get('logout', [LoginController::class, 'logout'])->name('guest.logout');
Route::middleware('is_approved')->post('change-password', [LoginController::class, 'changePassword'])
    ->name('guest.change-password');
Route::get('send-otp-forgot-password', [ForgotPasswordController::class, 'forgotPassword'])
    ->name('guest.forgot-password');
Route::post('check-otp-forgot-password', [ForgotPasswordController::class, 'checkOtp'])
    ->name('guest.check-otp');
Route::post('change-forgot-password', [ForgotPasswordController::class, 'changeForgotPassword'])
    ->name('guest.change-forgot-password');
Route::post('resgister', [ResgisterController::class, 'resgister'])
    ->name('guest.resgister');

Route::get('/', function () {
    return redirect()->route('home');
});

// user and creator

Route::middleware('is_approved')->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/category/{category}', [HomeController::class, 'category'])->name('list-category');
    Route::get('/post/{slugPost}/{id?}', [HomeController::class, 'post'])->name('post');
    Route::get('/search/{search}', [HomeController::class, 'search'])->name('search');    
    Route::get('/creator/{id}', [HomeController::class, 'creator'])->name('creator');
    Route::get('/lien-he', [HomeController::class, 'aboutUs'])->name('about-us');    
});

Route::middleware([
    'is_approved',
    'auth:accounts',
])->prefix('user')->name('user.')->group(function () {
    Route::get('my-profile', [UserController::class, 'myProfile'])->name('my-profile');

    Route::middleware('is_creator')->group(function () {
        Route::get('my-post', [CreatorController::class, 'listPosts'])->name('my-post');

        Route::get('follower', [CreatorController::class, 'lisFollowers'])->name('list-follower');
    });
});


// admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('check-otp-user', [AdminLoginController::class, 'sendOtp'])->name('check-otp');

    Route::middleware(['guest:' . config('fortify.guard')])
        ->get('/login', [AdminLoginController::class, 'formLogin'])->name('login');

    Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware([
    'is_admin'
])->prefix('admin')->name('admin.')->group(function (){
    Route::get('/dashboard', [AdminHomeController::class, 'home'])->name('dashboard');

    Route::get('/accounts', [AdminHomeController::class, 'listUser'])->name('list-account');

    Route::get('/categories', [AdminHomeController::class, 'listCategory'])->name('list-category');

    Route::get('/posts', [AdminHomeController::class, 'listPost'])->name('list-post');       

    Route::get('/term-of-use', [AdminHomeController::class, 'termOfUse'])->name('term-of-use');

    Route::get('/private-policy', [AdminHomeController::class, 'privatePolicy'])->name('private-policy');

    Route::get('/about-us', [AdminHomeController::class, 'AboutUs'])->name('about-us');
});