<?php

use App\Http\Controllers\Moonshine\LeadActionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'middleware' => array_merge(config('moonshine.middleware', []), config('moonshine.auth.middleware', [])),
    'prefix' => config('moonshine.prefix'),
    'as' => 'moonshine.',
    'domain' => config('moonshine.domain'),
], function (): void {
    Route::get('leads/{lead}/accept', [LeadActionsController::class, 'accept'])->name('leads.accept');
});
