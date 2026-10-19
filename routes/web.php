<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\MemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/memos', [MemoController::class, 'index'])->name('memos.index');
    Route::get('/memos/create', [MemoController::class, 'create'])->name('memos.create');
    Route::post('/memos', [MemoController::class, 'store'])->name('memos.store');

    Route::get('/memos/{memo}/edit', [MemoController::class, 'edit'])->name('memos.edit');
    Route::put('/memos/{memo}', [MemoController::class, 'update'])->name('memos.update');
    Route::delete('/memos/{memo}', [MemoController::class, 'destroy'])->name('memos.destroy');

    Route::get('/memos/{memo}', [MemoController::class, 'show'])->name('memos.show');
    Route::get('/memos/{memo}/print', [MemoController::class, 'print'])->name('memos.print');
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{recipient}', [InboxController::class, 'show'])->name('inbox.show');
    Route::post('/inbox/{recipient}/process', [InboxController::class, 'process'])->name('inbox.process');
});


Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {

    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::get('/departments', [App\Http\Controllers\DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [App\Http\Controllers\DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [App\Http\Controllers\DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{department}/edit', [App\Http\Controllers\DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{department}', [App\Http\Controllers\DepartmentController::class, 'update'])->name('departments.update');
    Route::patch('/departments/{department}/toggle-status', [App\Http\Controllers\DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');
});
