<?php

use App\Http\Controllers\AdjuntosController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TiposController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\YouTubeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authenticated routes
Route::middleware(['auth.sode'])->group(function () {

});

// EndPoint Estados
Route::get('/estados', [EstadosController::class, 'lista']);
Route::post('/estados/paginado', [EstadosController::class, 'paginado']);
Route::post('/estados', [EstadosController::class, 'crear']);
Route::delete('/estados/{id}', [EstadosController::class, 'eliminar']);

// EndPoint Categorias
Route::get('/categorias', [CategoriasController::class, 'lista']);
Route::post('/categorias/paginado', [CategoriasController::class, 'paginado']);
Route::post('/categorias', [CategoriasController::class, 'crear']);
Route::patch('/categorias/{id}', [CategoriasController::class, 'estado']);
Route::delete('/categorias/{id}', [CategoriasController::class, 'eliminar']);

// EndPoint Tipos
Route::get('/tipos', [TiposController::class, 'lista']);
Route::post('/tipos/paginado', [TiposController::class, 'paginado']);
Route::post('/tipos', [TiposController::class, 'crear']);
Route::patch('/tipos/{id}', [TiposController::class, 'estado']);
Route::delete('/tipos/{id}', [TiposController::class, 'eliminar']);

// EndPoint Usuarios
Route::get('/usuarios', [UsuariosController::class, 'lista']);
Route::post('/usuarios/paginado', [UsuariosController::class, 'paginado']);
Route::post('/usuarios', [UsuariosController::class, 'crear']);
Route::patch('/usuarios/{id}', [UsuariosController::class, 'estado']);
Route::delete('/usuarios/{id}', [UsuariosController::class, 'eliminar']);

// EndPoint Tickets
Route::post('/tickets/paginado', [TicketsController::class, 'paginado']);
Route::post('/tickets', [TicketsController::class, 'crear']);
Route::patch('/tickets/estado', [TicketsController::class, 'actualizarEstado']);
Route::patch('/tickets/responsable', [TicketsController::class, 'actualizarResponsable']);

// EndPoint Adjuntos
Route::get('/adjuntos/{id}', [AdjuntosController::class, 'obtener']);
Route::get('/adjuntos/ticket/{ticket}', [AdjuntosController::class, 'obtenerPorTicket']);
Route::post('/adjuntos', [AdjuntosController::class, 'crear']);
