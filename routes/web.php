<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\CredencialController;

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return view('home');
});
Route::get('/credencial', function () {
    return view('credenciales.credencial');
})->name('credencial');

Route::get('/cliente', function () {
    return view('cliente.cliente');
})->name('cliente');


Route::get('/cliente', [PersonaController::class,'index'])->name('cliente');
Route::get('/buscar-persona/{ci}', [PersonaController::class,'buscar'])->name('buscar-persona');

Route::post('/credenciales/imprimir',[CredencialController::class,'imprimirMasivo']);