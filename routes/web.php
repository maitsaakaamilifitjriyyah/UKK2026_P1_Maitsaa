<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('welcome');

Route::get('/dashboard', function () {
    $role = strtolower(auth()->user()->role);
    if ($role == 'admin') {
        return view('dashboard');
    } elseif ($role == 'employee') {
        return view('dashboard');
    } elseif ($role == 'user') {
        return redirect()->route('item.index');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/location', [LocationController::class, 'index'])->name('location.index');
Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
Route::post('/location', [LocationController::class, 'store'])->name('location.store');
Route::get('/location/{id}/edit', [LocationController::class, 'edit'])->name('location.edit');
Route::put('/location/{id}', [LocationController::class, 'update'])->name('location.update');
Route::delete('/location/{id}', [LocationController::class, 'destroy'])->name('location.destroy');

Route::get('/item', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');
Route::post('/item', [ItemController::class, 'store'])->name('item.store');
Route::get('/item/{id}/edit', [ItemController::class, 'edit'])->name('item.edit');
Route::put('/item/{id}', [ItemController::class, 'update'])->name('item.update');
Route::delete('/item/{id}', [ItemController::class, 'destroy'])->name('item.destroy');
Route::get('/item/{id}', [ItemController::class, 'detail'])->name('item.detail');

Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');
Route::get('/unit/{code}', [UnitController::class, 'edit'])->name('unit.edit');
Route::put('/unit/{code}', [UnitController::class, 'update'])->name('unit.update');
Route::delete('/unit/{code}', [UnitController::class, 'destroy'])->name('unit.destroy');

Route::get('/loan', [LoanController::class, 'index'])->name('loan.index');
Route::get('/loan/create', [LoanController::class, 'create'])->name('loan.create');
Route::post('/loan', [LoanController::class, 'store'])->name('loan.store');
Route::get('/loan/{id}/edit', [LoanController::class, 'edit'])->name('loan.edit');
Route::put('/loan/{id}', [LoanController::class, 'update'])->name('loan.update');
Route::put('/loan/{id}/approve', [LoanController::class, 'approve'])->name('loan.approve');
Route::put('/loan/{id}/reject', [LoanController::class, 'reject'])->name('loan.reject');
Route::put('/loan/{id}/return', [LoanController::class, 'return'])->name('loan.return');
Route::delete('/loan/{id}', [LoanController::class, 'destroy'])->name('loan.destroy');

Route::get('/returns/history', [ReturnController::class, 'history'])->name('return.history');
Route::get('/returns', [ReturnController::class, 'index'])->name('return.index');
Route::put('/returns/{id}/check', [ReturnController::class, 'check'])->name('return.check');

Route::get('/log', [ActivityLogController::class, 'index'])->name('log.index');
require __DIR__.'/auth.php';