<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubEventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsistenciaController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

Route::get('login', function () {
    return view('home');
})->name('login');

Route::get('/', function () {
    return view('home');
});


Route::middleware(['auth', 'role:ADMINISTRADOR,MODERADOR'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });

    Route::controller(EventController::class)->group(function() {
        Route::get('eventos', 'list')->name('eventos');
        Route::post('eventos', 'store');
        Route::patch('eventos/{eventId}', 'update');
    });

    Route::controller(SubEventController::class)->group(function () {
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
    Route::post('/cliente', [PersonaController::class, 'store']);
    Route::patch('/cliente/{personaId}', [PersonaController::class, 'update']);
    Route::delete('/cliente/{personaId}', [PersonaController::class, 'delete']);

    Route::post('/credenciales/preview', [CredencialController::class, 'preview']);

    Route::get('/buscar-cliente/{ci}', [AsistenciaController::class, 'buscarCliente']);
    
    // Registrar asistencia (POST)
    Route::post('/registrar-asistencia', [AsistenciaController::class, 'registrar'])->name('asistencia.registrar');

    // Ruta para mostrar la lista de asistencia
    Route::get('/asistencia/{id}', [AsistenciaController::class, 'index'])->name('asistencia.index');

    // Ruta para procesar el registro (la que usará el botón "Aceptar" de tu modal)
    Route::post('/asistencia/registrar', [AsistenciaController::class, 'registrar'])->name('asistencia.registrar');

    Route::get('/asistencia/exportar/{subEventId}', [AsistenciaController::class, 'exportToExcel']);
});

Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('usuarios', 'list')->name('usuarios');
        Route::post('usuarios', 'store');
        Route::patch('usuarios/{userId}', 'update');
    });
});
