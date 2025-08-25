<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\AdminDashboard;
use App\Livewire\AdminConfig;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Safety;
use App\Livewire\Admin\UserProfile;



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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/', AdminDashboard::class)->name('admin.index');
        Route::get('/config', AdminConfig::class)->name('admin.config');
        Route::get('/users', Users::class)->name('admin.users');
        Route::get('/admin/activities', Safety::class)->name('admin.activities');
        Route::get('/admin/user/{userId}', UserProfile::class)->name('admin.user-profile');

    });
});
