<?php

use App\Http\Controllers\OpenPlatformController;
use App\Http\Controllers\OpenPlatformServerController;
use App\Http\Controllers\SubPlatformController;
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
/** 开放平台对微信官方接口，无需认证身份 */
Route::group([
    'prefix' => 'op/{opSlug}',
    'middleware' => ['platform.op']
], function (\Illuminate\Routing\Router $router) {
    // 开放平台接收微信官方消息
    $router->post('serve', [OpenPlatformServerController::class, 'serve'])->name('openPlatformServe');
    // 代公众平台接收用户消息
    $router->post('{appId}/notify', [OpenPlatformServerController::class, 'notify'])->name('platformNotify');
    // 绑定开放平台页面
    $router->get('bind', [OpenPlatformController::class, 'bind'])->name('bind');
    // 绑定开放平台回调页面
    $router->get('bind-callback', [OpenPlatformController::class, 'bindCallback'])->name('bindCallback');
});

/** 登录 */
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::get('platform-media/{appId}/{type}/{fileName}', [\App\Http\Controllers\PlatformController::class, 'getLocalMedia'])->middleware('media.token')->name('platform-media');

/** 首页平台列表相关 */
Route::group(['middleware' => ['auth:api']], function (\Illuminate\Routing\Router $router) {
    $router->apiResource('platforms', \App\Http\Controllers\PlatformController::class);
});

/** 开放平台代授权方实现业务 */
Route::group([
    'prefix' => 'open-platform/{opId}',
    'middleware' => ['auth:api', 'platform.op']
], function (\Illuminate\Routing\Router $router) {
    $router->get('secret-config', [OpenPlatformController::class, 'getSecretConfig']);
    $router->get('authorizers', [OpenPlatformController::class, 'getAuthorizerList']);
    $router->get('authorizers/{appId}', [OpenPlatformController::class, 'getAuthorizer']);
    $router->post('createBetaMiniProgram', [OpenPlatformController::class, 'createBetaMiniProgram']);

    /** 存储已授权的子平台详细信息在数据库方便后续查看 */
    $router->apiResource('sub-platforms', SubPlatformController::class)->only(['index', 'store', 'show', 'destroy']);

    /** 开放平台代小程序实现功能 */
    Route::group([
        'prefix' => 'mp/{appId}',
    ], function (\Illuminate\Routing\Router $router) {
        $router->get('basic-info', [\App\Http\Controllers\SubMiniProgramController::class, 'basicInfo']);
        $router->post('delete-local-media', [\App\Http\Controllers\SubMiniProgramController::class, 'deleteLocalMedia']);
        $router->post('local-media', [\App\Http\Controllers\SubMiniProgramController::class, 'uploadLocalMedia']);
        $router->get('local-media', [\App\Http\Controllers\SubMiniProgramController::class, 'getLocalMediaList']);
        $router->get('local-media/{fileName}', [\App\Http\Controllers\SubMiniProgramController::class, 'getLocalMedia']);
        $router->post('upload-template-media', [\App\Http\Controllers\SubMiniProgramController::class, 'uploadTemplateMedia']);
        $router->get('testers', [\App\Http\Controllers\SubMiniProgramController::class, 'testers']);
        $router->post('testers', [\App\Http\Controllers\SubMiniProgramController::class, 'bindTester']);
        $router->delete('testers/{userSlug}', [\App\Http\Controllers\SubMiniProgramController::class, 'unBindTester']);

        $router->post('checkNickName', [\App\Http\Controllers\SubMiniProgramController::class, 'checkNickName']);
        $router->post('getNicknameAuditStatus', [\App\Http\Controllers\SubMiniProgramController::class, 'getNicknameAuditStatus']);
        $router->post('setNickName', [\App\Http\Controllers\SubMiniProgramController::class, 'setNickName']);
        $router->post('setAvatar', [\App\Http\Controllers\SubMiniProgramController::class, 'setAvatar']);
        $router->post('setSignature', [\App\Http\Controllers\SubMiniProgramController::class, 'setSignature']);
    });

    /** 开放平台代公众号实现功能 */
    Route::group([
        'prefix' => 'oa/{appId}',
    ], function (\Illuminate\Routing\Router $router) {

    });
});
