<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\voterController;

Route::get('/', function () {
    return "api ok";
});


Route::get('/voters', [voterController::class, "getAllVoters"]);

Route::get('/voters/{votanteId}', [voterController::class, "findVoterById"]);

Route::post('/voters', [voterController::class, "createVoter"]);

Route::put('/voters/{votanteId}', [voterController::class, "updateVoterById"]);

Route::delete('/voters/{votanteId}', [voterController::class, "deleteVoterById"]);
