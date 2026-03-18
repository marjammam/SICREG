<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\SubEventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\CredencialController;

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', function () {
    return view('home');
});

Route::controller(EventController::class)->group(function() {
    Route::get('eventos', 'list');
    Route::post('eventos', 'store');
    Route::patch('eventos/{eventId}', 'update');
});

Route::controller(SubEventController::class)->group(function() {
    Route::get('subeventos/evento/{eventId}', 'listByEventId')->name('subeventos.evento');
    Route::post('subeventos', 'store');
    Route::patch('subeventos/{subEventId}', 'update');
    Route::delete('subeventos/{subEventId}', 'delete');
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