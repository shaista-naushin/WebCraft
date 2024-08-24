<?php

use Illuminate\Support\Facades\Route;
use Modules\Website\Http\Controllers\WebsiteController;

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

Route::prefix('admin/modules/website')->middleware(['verified', 'auth', 'web'])->group(function() {
    Route::get('/settings', [WebsiteController::class, 'settings']);
    Route::post('/settings', [WebsiteController::class, 'saveSettings']);
});
