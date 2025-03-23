<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoterController;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\Admin as AdminMiddleware;

// PRIVATE ROUTES
Route::get('/', function () {
    return "api ok";
});

Route::post('/login', function(){
    return "login desde api";
}/* [AuthController::class, "login"] */);
Route::post('/register', function(){
    return "register desde api";
}/* [AuthController::class, "register"] */);

Route::middleware([AdminMiddleware::class])->group(function(){
    
    Route::get('/logout', [AuthController::class, "logout"]);

    Route::get('/voters', [VoterController::class, "getAllVoters"]);
    
    Route::get('/voters/{votanteId}', [VoterController::class, "findVoterById"]);
    
    Route::post('/voters', [VoterController::class, "createVoter"]);
    
    Route::put('/voters/{votanteId}', [VoterController::class, "updateVoterById"]);
    
    Route::delete('/voters/{votanteId}', [VoterController::class, "deleteVoterById"]);
});


