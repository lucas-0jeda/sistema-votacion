<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name("home");

Route::get('/register', function(){
    return "formulario register";
});

Route::get('/login', function(){
    return "formulario login";
});
