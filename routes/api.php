<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeasiswaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API untuk Beasiswa
Route::prefix('beasiswa')->group(function () {
    // Get all beasiswa (dengan pagination dan filter)
    Route::get('/', [BeasiswaController::class, 'apiIndex']);
    
    // Get beasiswa by ID
    Route::get('/{id}', [BeasiswaController::class, 'apiShow']);
});
