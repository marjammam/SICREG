<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubEventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CredencialPersonaController;

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
        Route::post('subeventos/evento/{eventId}', 'listByEventId');
    });

    Route::get('/credencial', function () {
        return view('credenciales.credencial');
    })->name('credencial');

    /*Route::get('/cliente', function () {
        return view('cliente.cliente');
    })->name('cliente');*/


    Route::match(['get', 'post'], '/cliente', [PersonaController::class,'index'])->name('cliente');
    Route::get('/buscar-persona/{ci}', [PersonaController::class,'buscar'])->name('buscar-persona');
    Route::post('/cliente/store', [PersonaController::class, 'store']);
    Route::patch('/cliente/{personaId}', [PersonaController::class, 'update']);
    Route::delete('/cliente/{personaId}', [PersonaController::class, 'delete']);
    Route::get('/cliente/fotos/{filename}', [PersonaController::class, 'obtenerFoto'])->name('fotos.obtener');


    Route::post('/credenciales/preview', [CredencialController::class, 'preview']);
    //Route::post('/credencial-persona', [CredencialPersonaController::class, 'store']);
    Route::get('/credenciales/excel', [CredencialController::class, 'exportToExcel'])->name('credenciales.excel');
   // Route::get('/credencial-persona/exportar', [CredencialPersonaController::class, 'exportToExcel'])->name('credenciales.exportar');
    //ruta registro de credencial al imprimir
    Route::post('/credencial-persona', [CredencialController::class, 'store']);
    Route::get('/credenciales/lista', [CredencialController::class, 'index'])->name('credenciales.lista');



    // Rutas para asistencia
    Route::get('/asistencia/verificar', [AsistenciaController::class, 'verificar'])->name('asistencia.verificar');
    Route::get('/asistencia/buscar-ci', [AsistenciaController::class, 'buscarPorCI'])->name('asistencia.buscarPorCI');
    Route::get('/buscar-cliente/{ci}', [AsistenciaController::class, 'buscarCliente']);
    Route::get('/asistencia/exportar/{subEventId}', [AsistenciaController::class, 'exportToExcel']);
    Route::delete('/asistencia/{asistenciaId}', [AsistenciaController::class, 'delete']);
    Route::match(['get', 'post'], '/asistencia/{id}', [AsistenciaController::class, 'index'])->name('asistencia.index');
    Route::post('/registrar-asistencia', [AsistenciaController::class, 'registrar'])->name('asistencia.registrar');

    Route::get('/subeventos/listas', [SubEventController::class, 'listaSubevento'])->name('subeventos.listas');
    Route::get('/subeventos/exportar', [SubEventController::class, 'exportarExcel'])->name('subeventos.exportar');






});

Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('usuarios', 'list')->name('usuarios');
        Route::post('usuarios', 'list');
        Route::post('usuarios/store', 'store')->name('usuarios.store');
        Route::patch('usuarios/{userId}', 'update');
    });
});

 