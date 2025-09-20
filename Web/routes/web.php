<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StoreSettingController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// Home page
Route::get('/', [HomeController::class, 'index']);
Route::get('/about-us', [HomeController::class, 'about']);
Route::get('/menus', [HomeController::class, 'menu']);
Route::get('/contact-us', [HomeController::class, 'contact']);
Route::get('/reservation', [HomeController::class, 'reservation']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// User CRUD
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/adding',  [UserController::class, 'adding']);
Route::post('/users', [UserController::class, 'create']);
Route::get('/users/{id}',  [UserController::class, 'edit']);
Route::put('/users/{id}',  [UserController::class, 'update']);
Route::delete('/users/remove/{id}',  [UserController::class, 'remove']);
Route::get('/users/reset/{id}',  [UserController::class, 'reset']);
Route::put('/users/reset/{id}',  [UserController::class, 'resetPassword']);

// Table CRUD
Route::get('/table', [TableController::class, 'index']);
Route::get('/table/adding',  [TableController::class, 'adding']);
Route::post('/table',  [TableController::class, 'create']);
Route::get('/table/{id}',  [TableController::class, 'edit']);
Route::put('/table/{id}',  [TableController::class, 'update']);
Route::delete('/table/remove/{id}',  [TableController::class, 'remove']);

// Menu CRUD
Route::get('/menu', [MenuItemController::class, 'index']);
Route::get('/menu/adding',  [MenuItemController::class, 'adding']);
Route::post('/menu',  [MenuItemController::class, 'create']);
Route::get('/menu/{id}',  [MenuItemController::class, 'edit']);
Route::put('/menu/{id}',  [MenuItemController::class, 'update']);
Route::delete('/menu/remove/{id}',  [MenuItemController::class, 'remove']);

// Store settings
Route::get('/store-settings', [StoreSettingController::class, 'index']);
Route::put('/store-settings/update', [StoreSettingController::class, 'update']);
Route::post('/store-settings/reset', [StoreSettingController::class, 'reset']);
