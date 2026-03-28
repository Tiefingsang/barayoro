<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Page d'accueil
Route::get('/', function () {
    return view('dashboard.index');
});


