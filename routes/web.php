<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name("home");

Route::get('/sign-in', function(){
    return Inertia::render('login/Login');
})->name("sign-in");

// PRIVATE ROUTES
Route::middleware("admin")->group(function(){
    Route::get('/dashboard', function () {
        return Inertia::render('dashboard');
    });
});
