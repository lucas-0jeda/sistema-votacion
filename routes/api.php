<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoterController;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VoteController;
use App\Http\Middleware\Admin as AdminMiddleware;
use App\Http\Middleware\User as UserMiddleware;

// PUBLIC ROUTES
Route::middleware("user")->group(function(){   
    Route::post('/login', [AuthController::class, "login"]);
    Route::post('/register', [AuthController::class, "register"]);
    Route::post('/vote', [VoteController::class, "insertVote"]);
    Route::get('/candidates', [VoterController::class, "getCandidates"]);

});

// PRIVATE ROUTES
Route::middleware("admin")->group(function(){
    
    Route::get('/logout', [AuthController::class, "logout"]);

    Route::get('/voters', [VoterController::class, "getAllVoters"]);
    
    Route::get('/voters/{votanteId}', [VoterController::class, "findVoterById"]);
    
    Route::post('/voters', [VoterController::class, "createVoter"]);
    
    Route::put('/voters/{votanteId}', [VoterController::class, "updateVoterById"]);
    
    Route::delete('/voters/{votanteId}', [VoterController::class, "deleteVoterById"]);

});


