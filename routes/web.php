<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/clear-cache', function () {
    
    // Clear route cache
    Artisan::call('route:clear');
    // Clear config cache
    Artisan::call('route:cache');

    
    return "Cache cleared successfully!";
});