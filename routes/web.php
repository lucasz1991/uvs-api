<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\AdminDashboard;
use App\Livewire\AdminMessageBox;
use App\Livewire\AdminConfig;
use App\Livewire\WebContentManager;
use App\Livewire\Admin\RatingStructure\Index;
use App\Livewire\Admin\Exports;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\Safety;
use App\Livewire\Admin\Employees;
use App\Livewire\Admin\MailManagement;
use App\Livewire\Admin\UserProfile;
use App\Livewire\Admin\ManageContacts;
use App\Livewire\Admin\Cms\EditProject;
use App\Livewire\Admin\Courses\CourseList;


use App\Http\Controllers\PagebuilderProjectController;



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
        Route::get('/web-content-manager', WebContentManager::class)->name('admin.webcontentmanager');
        Route::get('/rating-structure', Index::class)->name('admin.ratingstructure.index');
        Route::get('/adminmessages', AdminMessageBox::class)->name('admin.messages');
         
        //Route::get('/exports', Exports::class)->name('admin.exports'); 
        Route::get('/users', Users::class)->name('admin.users'); 
        Route::get('/admin/safety', Safety::class)->name('admin.safety');
        Route::get('/admin/employees', Employees::class)->name('admin.employees');
        Route::get('/admin/mails', MailManagement::class)->name('admin.mails');
        Route::get('/admin/user/{userId}', UserProfile::class)->name('admin.user-profile');
        //Route::get('/admin/contacts', ManageContacts::class)->name('admin.contacts');
        Route::get('/admin/cms/edit-project/{projectId?}', EditProject::class)->name('admin.cms.edit-project');

        Route::get('/courses', CourseList::class)->name('courses.index');


        Route::post('/admin/pagebuilder/save', [PagebuilderProjectController::class, 'save']);
        Route::get('/admin/pagebuilder/load/{name}', [PagebuilderProjectController::class, 'load']);
        Route::post('/admin/pagebuilder/upload', [PagebuilderProjectController::class, 'uploadImage']);
        Route::get('/admin/pagebuilder/assets', [PagebuilderProjectController::class, 'getAssets']);
    });
});
