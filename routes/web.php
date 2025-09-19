<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/task/new', [TaskController::class, 'create'])->name('task.create');
Route::post('/task/store', [TaskController::class, 'store'])->name('task.store');
Route::get('/task/edit', [TaskController::class, 'edit'])->name('task.edit');
Route::put('/task/update', [TaskController::class, 'update'])->name('task.update');
Route::get('/task/delete', [TaskController::class, 'delete'])->name('task.delete');
Route::post('/task/update-status', [TaskController::class, 'updateStatus'])->name('task.updateStatus');
Route::get('/task', [TaskController::class, 'index'])->name('task.view');

Route::get('/category/new', [CategoryController::class, 'create'])->name('category.create');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');

Route::get('/login',[AuthController::class, 'index'])->name('login');
Route::get('/register',[AuthController::class, 'register'])->name('register');
