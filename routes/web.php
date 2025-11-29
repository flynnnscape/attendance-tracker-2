<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class);
Route::resource('roles', RoleController::class);
Route::resource('attendance', AttendanceController::class);

Route::get('/departments', function () {
    return Inertia::render('Departments/Index');
});
Route::get('/payroll', function () {
    return Inertia::render('Payroll/Admin');
}) ;
Route::get('/schedules', function () {
    return Inertia::render('Schedules/Index');
}) ;
Route::get('/reports', function () {
    return Inertia::render('Reports/Index');
}) ;

require __DIR__.'/settings.php';
