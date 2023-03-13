<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    JobController
};

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

Route::group(['prefix' => 'v1'], function() {

    Route::group(['prefix' => 'auth'], function() {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::group(['middleware' => ['auth:sanctum']],function() {

        Route::group(['prefix' => 'auth'], function() {
            Route::get('/user', [AuthController::class, 'user']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        Route::group(['prefix' => 'jobs'], function() {
            Route::post('/', [JobController::class, 'store']);
            Route::put('/{id}', [JobController::class, 'update']);
            Route::delete('/{id}', [JobController::class, 'destroy']);
        });

    });

    Route::group(['prefix' => 'jobs'], function() {
        Route::get('/', [JobController::class, 'index']);
        Route::get('/{id}', [JobController::class, 'show']);
    });

    Route::group(['prefix' => 'search'], function() {
        Route::get('/jobs', [JobController::class, 'search']);
    });

    Route::group(['prefix' => 'filter'], function() {
        Route::get('/jobs', [JobController::class, 'filter']);
    });

});

