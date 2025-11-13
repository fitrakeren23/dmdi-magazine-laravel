<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return redirect('/id');
});

// Multilingual Frontend Routes
Route::get('/{locale}', [HomeController::class, 'index'])->where('locale', 'id|en')->name('frontend.home');
Route::get('/{locale}/article/{slug}', [FrontendController::class, 'showArticle'])->where('locale', 'id|en')->name('frontend.article.show');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
// Admin Auth Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin Protected Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Article Management
    Route::resource('/articles', AdminArticleController::class);
    Route::post('/articles/{id}/generate-qr', [AdminArticleController::class, 'generateQR'])->name('articles.generate-qr');
});

// Create admin user route for testing
Route::get('/create-admin', function () {
    \App\Models\User::create([
        'name' => 'Admin DMDI',
        'email' => 'admin@dmdi.com',
        'password' => bcrypt('password123'),
        'is_admin' => true
    ]);
    return 'Admin user created!';
});