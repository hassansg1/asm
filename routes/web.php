<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::group(['middleware' => ['auth']], function () {
    Route::post('clearSession', [App\Http\Controllers\HomeController::class, 'clearSession']);


    Route::resources([
        'site' => \App\Http\Controllers\SiteController::class,
        'room' => \App\Http\Controllers\RoomController::class,
        'role' => \App\Http\Controllers\RoleController::class,
        'user' => \App\Http\Controllers\UserController::class,
        'unit' => \App\Http\Controllers\UnitController::class,
        'asset' => \App\Http\Controllers\AssetController::class,
        'company' => \App\Http\Controllers\CompanyController::class,
        'cabinet' => \App\Http\Controllers\CabinetController::class,
        'subsite' => \App\Http\Controllers\SubSiteController::class,
        'building' => \App\Http\Controllers\BuildingController::class,
        'permission' => \App\Http\Controllers\PermissionController::class,
    ]);
    Route::post('unit/filter', [App\Http\Controllers\UnitController::class, 'filter'])->name('unit.filter');
});
