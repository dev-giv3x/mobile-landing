<?php

use App\Http\Controllers\ModulesController;
use App\Http\Controllers\LeadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::get('/modules', [ModulesController::class, 'index']);

Route::post('/send-form', [LeadController::class, 'store']);
