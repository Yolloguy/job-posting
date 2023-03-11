<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;

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


Route::middleware('auth:sanctum')->group(function () {
    Route::post('jobs', 'App\Http\Controllers\JobController@store');
    Route::put('jobs/{id}', 'App\Http\Controllers\JobController@update');
    Route::delete('jobs/{id}', 'App\Http\Controllers\JobController@destroy');
});

Route::get('jobs', 'App\Http\Controllers\JobController@index');
Route::get('jobs/{id}', 'App\Http\Controllers\JobController@show');
Route::get('jobs/search', 'App\Http\Controllers\JobController@search');
Route::middleware('auth:sanctum')->get('jobs/filter', 'App\Http\Controllers\JobController@filter');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'login']);
// Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
