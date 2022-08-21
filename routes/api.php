<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/user', [AuthController::class, 'userProfile']);
    Route::post('/password', [AuthController::class, 'changePassWord']);
    Route::post('/role', [AuthController::class, 'addRole']);
    Route::post('/delete', [AuthController::class, 'delete']);
    Route::post('/all', [AuthController::class, 'allUser']);
});

Route::group(['prefix' => 'brand'], function () {
    Route::post('/index', [BrandController::class, 'index']);
    Route::post('/show/{id}', [BrandController::class, 'show']);
    Route::post('/store', [BrandController::class, 'store']);
    Route::post('/destroy', [BrandController::class, 'destroy']);
    Route::post('/update', [BrandController::class, 'update']);
});
