<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StoreSettingController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

// Home page
// Public Routes (ทุกคนเข้าได้)
Route::get('/', [HomeController::class, 'index']);
Route::get('/about-us', [HomeController::class, 'about']);
Route::get('/menus', [HomeController::class, 'menu'])->name('menu.index');
Route::get('/menus/search', [HomeController::class, 'searchMenu'])->name('menu.search');
Route::get('/contact-us', [HomeController::class, 'contact']);
Route::get('/reservation', [HomeController::class, 'reservation']);

// Authentication
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ทำไมต้องมี name('login') ?
// เวลาใช้ auth middleware ถ้า user ยังไม่ login → Laravel จะ redirect ไปหา route ที่ชื่อว่า login โดยอัตโนมัติ
// ถ้าไม่เจอ → มันก็โยน error Route [login] not defined.

// Dashboard + CRUD (เฉพาะ ADMIN)
// login เสร็จไปหน้า Dashboard
Route::middleware(['auth:user', 'role:ADMIN'])->group(function () {
    // User CRUD
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/adding', [UserController::class, 'adding']);
    Route::post('/users', [UserController::class, 'create']);
    Route::get('/users/{id}', [UserController::class, 'edit']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/remove/{id}', [UserController::class, 'remove']);
    Route::get('/users/reset/{id}', [UserController::class, 'reset']);
    Route::put('/users/reset/{id}', [UserController::class, 'resetPassword']);

    // Table CRUD
    Route::get('/table', [TableController::class, 'index']);
    Route::get('/table/adding', [TableController::class, 'adding']);
    Route::post('/table', [TableController::class, 'create']);
    Route::get('/table/{id}', [TableController::class, 'edit']);
    Route::put('/table/{id}', [TableController::class, 'update']);
    Route::delete('/table/remove/{id}', [TableController::class, 'remove']);

    // Menu CRUD
    Route::get('/menu', [MenuItemController::class, 'index']);
    Route::get('/menu/adding', [MenuItemController::class, 'adding']);
    Route::post('/menu', [MenuItemController::class, 'create']);
    Route::get('/menu/{id}', [MenuItemController::class, 'edit']);
    Route::put('/menu/{id}', [MenuItemController::class, 'update']);
    Route::delete('/menu/remove/{id}', [MenuItemController::class, 'remove']);

    // Store settings
    Route::get('/store-settings', [StoreSettingController::class, 'index']);
    Route::put('/store-settings/update', [StoreSettingController::class, 'update']);
    Route::post('/store-settings/reset', [StoreSettingController::class, 'reset']);
});

// Dashboard + CRUD (เฉพาะ ADMIN, STAFF)
Route::middleware(['auth:user', 'role:ADMIN,STAFF'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reservation CRUD
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/adding', [ReservationController::class, 'adding']);
    Route::post('/reservations', [ReservationController::class, 'create']);
    Route::get('/reservations/{id}', [ReservationController::class, 'edit']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/remove/{id}', [ReservationController::class, 'remove']);

    // Reservation Check-in
    Route::post('/reservations/checkin/{id}', [ReservationController::class, 'checkIn']);
});
