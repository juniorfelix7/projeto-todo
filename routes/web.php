<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Rotas públicas (sem autenticação)
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register');
Route::get('/user/profile', [AuthController::class, 'profile'])->name('user.profile');



// Rotas protegidas (com autenticação)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rotas de tarefas
    Route::get('/task/new', [TaskController::class, 'create'])->name('task.create');
    Route::post('/task/store', [TaskController::class, 'store'])->name('task.store');
    Route::get('/task/edit', [TaskController::class, 'edit'])->name('task.edit');
    Route::put('/task/update', [TaskController::class, 'update'])->name('task.update');
    Route::get('/task/delete', [TaskController::class, 'delete'])->name('task.delete');
    Route::post('/task/update-status', [TaskController::class, 'updateStatus'])->name('task.updateStatus');
    Route::get('/task', [TaskController::class, 'index'])->name('task.view');
    
    // Rotas de categorias
    Route::get('/category/new', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
});


Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
