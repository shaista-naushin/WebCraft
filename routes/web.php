<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\User\AutoresponderController;
use App\Http\Controllers\FormDataController;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\Admin\BlocksController;
use App\Http\Controllers\Admin\ComponentsController;
use App\Http\Controllers\Admin\PluginsController;
use App\Http\Controllers\Admin\ModulesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

Route::impersonate();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index']);
Route::get('/terms', [DashboardController::class, 'terms']);

Route::post('/capture/lead', [LeadController::class, 'captureLead']);
Route::post('/capture/default', [LeadController::class, 'captureDefault']);

Route::get('/pages/available/{type}', [PagesController::class, 'available']);

//Callbacks
Route::get('/aweber/callback', [AutoresponderController::class, 'aweberCallback']);
Route::get('/get_response/callback', [AutoresponderController::class, 'getResponseCallback']);

Route::get('/pages/view/{id}', [PagesController::class, 'viewPage']);

Route::middleware(['verified', 'auth', 'web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/form-data/list', [FormDataController::class, 'getAll']);
    Route::get('/form-data/delete/{id}', [FormDataController::class, 'destroy']);

    Route::get('/popup/list', [PopupController::class, 'getAll']);
    Route::get('/popup/create', [PopupController::class, 'create']);
    Route::post('/popup/create', [PopupController::class, 'save']);
    Route::get('/popup/edit/{id}', [PopupController::class, 'edit']);
    Route::post('/popup/edit/{id}', [PopupController::class, 'update']);
    Route::get('/popup/delete/{id}', [PopupController::class, 'destroy']);
    Route::get('/popup/enable/{id}', [PopupController::class, 'enable']);
    Route::get('/popup/disable/{id}', [PopupController::class, 'disable']);
    Route::get('/popup/duplicate/{id}', [PopupController::class, 'duplicate']);
    Route::get('/popup/editor/{id}', [PopupController::class, 'editor']);
    Route::get('/popup/editor/css/{id}', [PopupController::class, 'editorCSS']);
    Route::get('/popup/editor/js/{id}', [PopupController::class, 'editorJS']);
    Route::get('/popup/editor_frame/{id}', [PopupController::class, 'editorFrame']);
    Route::get('/popup/view/{id}', [PopupController::class, 'view']);

    Route::get('/popup/editor/{id}/load', [PopupController::class, 'editorLoad']);
    Route::post('/popup/editor/{id}/save', [PopupController::class, 'editorSave']);
    Route::post('/popup/upload/assets', [PopupController::class, 'uploadAsset']);

    Route::get('/pages/list', [PagesController::class, 'getAll']);
    Route::get('/pages/create', [PagesController::class, 'create']);
    Route::post('/pages/create', [PagesController::class, 'save']);
    Route::get('/pages/edit/{id}', [PagesController::class, 'edit']);
    Route::post('/pages/edit/{id}', [PagesController::class, 'update']);
    Route::get('/pages/delete/{id}', [PagesController::class, 'destroy']);
    Route::get('/pages/enable/{id}', [PagesController::class, 'enable']);
    Route::get('/pages/disable/{id}', [PagesController::class, 'disable']);
    Route::get('/pages/duplicate/{id}', [PagesController::class, 'duplicate']);
    Route::get('/pages/editor/{id}', [PagesController::class, 'editor']);
    Route::get('/pages/editor/css/{id}', [PagesController::class, 'editorCSS']);
    Route::get('/pages/editor/js/{id}', [PagesController::class, 'editorJS']);
    Route::get('/pages/editor_frame/{id}', [PagesController::class, 'editorFrame']);

    Route::get('/pages/editor/{id}/load', [PagesController::class, 'editorLoadPage']);
    Route::post('/pages/editor/{id}/save', [PagesController::class, 'editorSavePage']);
    Route::post('/pages/upload/assets', [PagesController::class, 'uploadAsset']);

    Route::prefix('user')->middleware([UserMiddleware::class])->namespace('User')->group(function () {
        Route::get('/autoresponder', [AutoresponderController::class, 'listAll']);
        Route::get('/autoresponder/list', [AutoresponderController::class, 'listAll']);

        Route::get('/connect/aweber', [AutoresponderController::class, 'aweber']);
        Route::get('/disconnect/aweber', [AutoresponderController::class, 'disconnectAweber']);

        Route::get('/connect/get_response', [AutoresponderController::class, 'getResponse']);
        Route::get('/disconnect/get_response', [AutoresponderController::class, 'disconnectGetResponse']);
    });

    Route::prefix('admin')->middleware([AdminMiddleware::class])->namespace('Admin')->group(function () {

        //Blocks routes
        Route::get('/blocks/list', [BlocksController::class, 'listAll']);
        Route::post('/blocks/install', [BlocksController::class, 'install']);
        Route::post('/blocks/create', [BlocksController::class, 'create']);
        Route::get('/blocks/edit/{id}', [BlocksController::class, 'edit']);
        Route::post('/blocks/edit/{id}', [BlocksController::class, 'update']);
        Route::get('/blocks/delete/{id}', [BlocksController::class, 'destroy']);
        Route::get('/blocks/enable/{id}', [BlocksController::class, 'enable']);
        Route::get('/blocks/disable/{id}', [BlocksController::class, 'disable']);

        //Components routes
        Route::get('/components/list', [ComponentsController::class, 'listAll']);
        Route::post('/components/install', [ComponentsController::class, 'install']);
        Route::post('/components/create', [ComponentsController::class, 'create']);
        Route::get('/components/edit/{id}', [ComponentsController::class, 'edit']);
        Route::post('/components/edit/{id}', [ComponentsController::class, 'update']);
        Route::get('/components/delete/{id}', [ComponentsController::class, 'destroy']);
        Route::get('/components/enable/{id}', [ComponentsController::class, 'enable']);
        Route::get('/components/disable/{id}', [ComponentsController::class, 'disable']);

        //Plugins routes
        Route::get('/plugins/list', [PluginsController::class, 'listAll']);
        Route::post('/plugins/install', [PluginsController::class, 'install']);
        Route::post('/plugins/create', [PluginsController::class, 'create']);
        Route::get('/plugins/edit/{id}', [PluginsController::class, 'edit']);
        Route::post('/plugins/edit/{id}', [PluginsController::class, 'update']);
        Route::get('/plugins/delete/{id}', [PluginsController::class, 'destroy']);
        Route::get('/plugins/enable/{id}', [PluginsController::class, 'enable']);
        Route::get('/plugins/disable/{id}', [PluginsController::class, 'disable']);

        //Modules routes
        Route::post('/modules/install', [ModulesController::class, 'installModule']);
        Route::get('/modules/list', [ModulesController::class, 'listModules']);
        Route::get('/modules/enable/{alias}', [ModulesController::class, 'changeStatus']);
        Route::get('/modules/disable/{alias}', [ModulesController::class, 'changeStatus']);
        Route::get('/modules/delete/{alias}', [ModulesController::class, 'deleteModule']);

        // Users Routes
        Route::post('/users/change_password', [UsersController::class, 'changePassword']);
        Route::get('/users/list', [UsersController::class, 'getAll']);

        Route::get('/users/create', [UsersController::class, 'create']);
        Route::post('/users/create', [UsersController::class, 'save']);

        Route::get('/users/edit/{id}', [UsersController::class, 'edit']);
        Route::post('/users/edit/{id}', [UsersController::class, 'update']);

        Route::get('/users/delete/{id}', [UsersController::class, 'destroy']);
        Route::get('/users/impersonate/{id}', [UsersController::class, 'impersonate']);

        Route::get('/users/send_verification/{id}', [UsersController::class, 'sendVerification']);
        Route::get('/users/change_status/{id}', [UsersController::class, 'changeStatus']);

        Route::get('/settings/site', [SettingsController::class, 'getSite']);
        Route::post('/settings/site', [SettingsController::class, 'updateSite']);

        Route::get('/settings/mailing', [SettingsController::class, 'getMailing']);
        Route::post('/settings/mailing', [SettingsController::class, 'updateMailing']);
    });
});
