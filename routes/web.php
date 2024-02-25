<?php

use App\Http\Controllers\ClientesActivosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ventasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/usuarios/', [UsuariosController::class, 'index']);
Route::get('/usuarios/nuevo', [UsuariosController::class, 'nuevousuario']);
Route::get('/usuarios/actualizar', [UsuariosController::class, 'actualizarusuario']);
Route::get('/clientesactivos', [ClientesActivosController::class, 'index']);
Route::get('/clientesactivos/nuevo', [ClientesActivosController::class, 'nuevousuario']);
Route::get('/clientesactivos/clienteCotizaciones', [ClientesActivosController::class, 'clienteCotizaciones']);
Route::get('/ventas', [ventasController::class, 'indexventas']);




