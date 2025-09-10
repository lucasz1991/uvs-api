<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParticipantApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/participants/store', [ParticipantApiController::class, 'store'])->name('participants.store');

Route::get('/participants', [ParticipantApiController::class, 'get'])->name('participants.get');

Route::get('/participants/{participant}/qualiprogram', [ParticipantApiController::class, 'getParticipantAndQualiprogram'])->name('participants.qualiprogram.get');


