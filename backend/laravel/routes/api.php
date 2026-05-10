<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\LeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/modules', [ModulesController::class, 'index']);
Route::get('/landings/{slug}', [LandingController::class, 'show']);

Route::post('/send-form', [LeadController::class, 'store']);
