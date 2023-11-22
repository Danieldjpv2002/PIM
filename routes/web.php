<?php

use App\ENV;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/assets/settings', [Controller::class, 'settings']);
Route::get('/assets/tinymce', function () {
    return redirect('https://cdn.tiny.cloud/1/k2389nfj5bxjbe7s3kkc3c6fututtaba9syfaviluaf2jew6/tinymce/6/tinymce.min.js');
});

Route::get('/login', function () {
    return view('login')->with('ENV', ENV::class);
});
Route::get('/', function () {
    return redirect('/inicio');
});
Route::get('/inicio', function () {
    return view('inicio')->with('ENV', ENV::class);
});
Route::get('/tickets', function () {
    return view('tickets')->with('ENV', ENV::class);
});
Route::get('/lista', function () {
    return view('lista')->with('ENV', ENV::class);
});
Route::get('/categorias', function () {
    return view('categorias')->with('ENV', ENV::class);
});
Route::get('/tipos', function () {
    return view('tipos')->with('ENV', ENV::class);
});
Route::get('/estados', function () {
    return view('estados')->with('ENV', ENV::class);
});
Route::get('/usuarios', function () {
    return view('usuarios')->with('ENV', ENV::class);
});
Route::get('/adjuntos', function () {
    return view('adjuntos')->with('ENV', ENV::class);
});

Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
});
