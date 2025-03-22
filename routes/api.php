<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\voterController;

Route::get('/', function () {
    return "api ok";
});


Route::get('/voters', [voterController::class, "getAllVoters"]);

Route::get('/voters/{votanteId}', function ($votanteId) {
    return "votante {$votanteId}";
});

Route::post('/voters/{votanteId}', function ($votanteId) {
    return "creando votante {$votanteId}";
});

Route::put('/voters/{votanteId}', function ($votanteId) {
    return "actualizando votante {$votanteId}";
});

Route::delete('/voters/{votanteId}', function ($votanteId) {
    return "borrando votante {$votanteId}";
});
