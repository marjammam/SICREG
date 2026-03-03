<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

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
