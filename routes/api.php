<?php

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

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function (\Illuminate\Routing\Router $router) {
    $router->apiResource('platforms', \App\Http\Controllers\PlatformController::class);
});
