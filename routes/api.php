<?php

use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TiposController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors']], function () {
    Route::get('webhook', [WebhookController::class, 'verify']);
    Route::post('webhook', [WebhookController::class, 'webhook']);
});

Route::get('get/{contact}', [WhatsAppController::class, 'get']);
Route::post('send/activity/issue', [WhatsAppController::class, 'sendActivityIssue']);
Route::post('send/contact', [WhatsAppController::class, 'sendContact']);
Route::get('audio/{media_id}', [WhatsAppController::class, 'getAudio']);
Route::get('youtube/audio/{media_id}', [YouTubeController::class, 'audio']);
Route::get('youtube/video/{media_id}', [YouTubeController::class, 'video']);

Route::post('github', [GitHubController::class, 'webhook']);
Route::get('github/banner/{username}', [GitHubController::class, 'banner']);

// Authenticated routes
Route::middleware(['auth.sode'])->group(function () {

    // Templates
    Route::post('/templates/paginate', [TemplatesController::class, 'paginate']);
    Route::post('/templates', [TemplatesController::class, 'create']);
    Route::delete('/templates/{id}', [TemplatesController::class, 'delete']);
    Route::patch('/templates/{id}', [TemplatesController::class, 'status']);
});

// EndPoint Estados
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

// EndPoint Tickets
Route::post('/tickets', [TicketsController::class, 'crear']);
