<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name("home");

Route::get('/sign-up', function(){
    return "formulario registro";
});

Route::get('/sign-in', function(){
    return "formulario login";
});

Route::get('/vote', function(){
    return "formulario votar";
});
