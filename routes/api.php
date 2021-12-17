<?php

use App\Http\Controllers\OpenPlatformController;
use App\Http\Controllers\OpenPlatformServerController;
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

Route::group([
    'prefix' => 'op/{opSlug}',
    'middleware' => ['platform.op']
], function (\Illuminate\Routing\Router $router) {
    // 开放平台接收微信官方消息
    $router->post('serve', [OpenPlatformServerController::class, 'serve'])->name('openPlatformServe');

    // 代公众平台接收用户消息
    $router->post('{appId}/notify', [OpenPlatformServerController::class, 'notify'])->name('platformNotify');
});

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function (\Illuminate\Routing\Router $router) {
    $router->apiResource('platforms', \App\Http\Controllers\PlatformController::class);
});

Route::group([
    'prefix' => 'open-platform/{opId}',
    'middleware' => ['auth:api', 'platform.op']
], function (\Illuminate\Routing\Router $router) {
    $router->get('secret-config', [OpenPlatformController::class, 'getSecretConfig']);
    $router->get('authorizers', [OpenPlatformController::class, 'getAuthorizerList']);
    $router->get('authorizers/{appId}', [OpenPlatformController::class, 'getAuthorizer']);
});
