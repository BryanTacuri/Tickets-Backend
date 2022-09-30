<?php

use App\Http\Controllers\HistorialIncidenciaController;
use App\Http\Controllers\SubsectionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Models\HistorialIncidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('me', 'me');
});


Route::controller(TicketController::class)->group(function () {
    Route::post('ticket', 'store');
    Route::get('ticket', 'index');
    Route::get('ticket/activar/{id}', 'activar');
    Route::get('ticket/{id}', 'getById');
    Route::put('ticket/{id}', 'update');
    Route::delete('ticket/{id}', 'delete');
});