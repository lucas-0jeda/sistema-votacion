<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoterController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VoteController;

// PUBLIC ROUTES
Route::middleware("user")->group(function(){   
    Route::post('/login', [AuthController::class, "login"])->name("api.login");
    Route::post('/register', [AuthController::class, "register"]);
    Route::post('/vote', [VoteController::class, "insertVote"]);
    Route::get('/able_to_vote/{docuent}', [VoterController::class, "ableToVote"]);
    Route::get('/candidates', [VoterController::class, "getCandidates"]);
});

// PRIVATE ROUTES
Route::middleware("admin")->group(function(){

    // Auth routes
    Route::get('/logout', [AuthController::class, "logout"]);
    Route::get('/me', [AuthController::class, "getUser"]);
    Route::put('/forbbidenPassword/{adminId}', [AuthController::class, "forbbidenPassword"]);
    
    // Voters routes
    Route::get('/voters', [VoterController::class, "getAllVoters"]);
    Route::get('/voters/{votanteId}', [VoterController::class, "findVoterById"]);
    Route::post('/voters', [VoterController::class, "createVoter"]);
    Route::put('/voters/{votanteId}', [VoterController::class, "updateVoterById"]);
    Route::delete('/voters/{votanteId}', [VoterController::class, "deleteVoterById"]);
    
    // Votes Routes
    Route::get('/votes/{voteId}', [VoteController::class, "getVoteDetails"]);
    Route::get('/votes', [VoteController::class, "getAllVotes"]);
    Route::get('/mostVotedCandidates', [VoteController::class, "getMostVotedCandidates"]);
});
