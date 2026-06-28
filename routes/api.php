<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\Api\DashboardApiController;

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

// API untuk Beasiswa Internal
Route::prefix('beasiswa')->group(function () {
    // Get all beasiswa (dengan pagination dan filter)
    Route::get('/', [BeasiswaController::class, 'apiIndex']);
    
    // Get beasiswa by ID
    Route::get('/{id}', [BeasiswaController::class, 'apiShow']);
});


// Public Health Check Endpoint
Route::get('/health', [DashboardApiController::class, 'health']);

// Submissions Endpoint - Proteksi JWT Token (Semua Role)
Route::middleware('jwt.auth')->group(function () {
    Route::get('/submissions', [DashboardApiController::class, 'submissions']);
});

// Pending Submissions Endpoint - Proteksi JWT Token Berdasarkan Role (Staff Only)
Route::middleware('jwt.auth:STAFF,WD3,KLI')->group(function () {
    Route::get('/submissions/pending', [DashboardApiController::class, 'submissionsPending']);
});

// API Group untuk Dashboard Informasi (Timelines & Opportunities)
Route::prefix('dashboard')->group(function () {
    Route::get('/timelines', [DashboardApiController::class, 'timelines']);
    Route::get('/opportunities', [DashboardApiController::class, 'opportunities']);
});

// Beasiswa Stats Endpoints
Route::middleware('jwt.auth')->prefix('stats')->group(function () {
    Route::get('/monitoring', [DashboardApiController::class, 'monitoring']);
    Route::get('/sebaran-jurusan', [DashboardApiController::class, 'sebaranJurusan']);
    Route::get('/sebaran-tipe-sumber', [DashboardApiController::class, 'sebaranTipeSumber']);
});
