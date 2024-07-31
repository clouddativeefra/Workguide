<?php

use App\Http\Controllers\ActividadesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\JefesController;
use App\Http\Controllers\TrabajadoresController;
use App\Http\Controllers\TareasController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PerfilController;
use App\Models\Admin;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('admin', AdminController::class);
Route::resource('areas', AreasController::class);
Route::resource('jefes', JefesController::class);
Route::resource('trabajadores', TrabajadoresController::class);
Route::resource('tareas', TareasController::class);
Route::resource('actividades', ActividadesController::class);

Route::middleware(['auth'])->group(function () {
    Route::resource('perfil', PerfilController::class)->only(['index', 'edit', 'update']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
