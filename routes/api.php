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
/** 开放平台对微信官方接口，无需认证身份 */
Route::group([
    'prefix' => 'open-platform-server/{openPlatformSlug}',
    'middleware' => ['platform.op']
], function (\Illuminate\Routing\Router $router) {
    // 开放平台接收微信官方消息
    $router->post('notify',
        [\App\Http\Controllers\OpenPlatform\NotifyController::class, 'store'])->name('openPlatformNotify');
    // 代公众平台接收用户消息
    $router->post('{appId}/notify',
        [\App\Http\Controllers\OpenPlatform\AuthorizerNotifyController::class, 'store'])->name('authorizerNotify');
});

/** 无需 json 格式化的文件下载 */
Route::get('open-platform/{openPlatformId}/mp/{appId}/code-test-qr', [\App\Http\Controllers\SubMiniProgramController::class, 'getTestQRCode'])->middleware(['auth:api', 'platform.op']);

Route::group([
    /** 统一格式化结果 */
    'middleware' => ['format.json', 'operation-log'],
], function (\Illuminate\Routing\Router $router) {
    /** 登录 */
    $router->post('login', [\App\Http\Controllers\AuthController::class, 'login']);

    /** 资源文件查看 */
    $router->get('platforms/{appId}/resources/{token}', [\App\Http\Controllers\ResourceController::class, 'show'])->name('resources.show');

    /** 首页平台列表相关操作 */
    $router->group(['middleware' => ['auth:api']], function (\Illuminate\Routing\Router $router) {
        $router->apiResource('platforms', \App\Http\Controllers\PlatformController::class);
        $router->apiResource('platforms/{appId}/resources', \App\Http\Controllers\ResourceController::class)->only(['index','store','destroy']);
        $router->apiResource('operation-logs', \App\Http\Controllers\OperationLogController::class)->only('index');
    });

    /** 开放平台相关 */
    $router->group([
        'prefix' => 'open-platform/{openPlatformId}',
        'middleware' => ['auth:api', 'platform.op']
    ], function (\Illuminate\Routing\Router $router) {
        $router->get('secret-config', [\App\Http\Controllers\OpenPlatform\OpenPlatformController::class, 'show']);
        $router->apiResource('wxa-server-domain', \App\Http\Controllers\OpenPlatform\WXAServerDomainController::class)->only(['index', 'update']);
        $router->get('domain-confirm-file', [\App\Http\Controllers\OpenPlatform\OpenPlatformController::class, 'webDomainValidateFile']);
        $router->apiResource('wxa-jump-domain', \App\Http\Controllers\OpenPlatform\WXAJumpDomainController::class)->only(['index', 'update']);

        $router->apiResource('authorizers', \App\Http\Controllers\OpenPlatform\AuthorizerController::class)->only(['index', 'show']);
        /** 存储已授权的子平台详细信息在数据库方便后续查看 */
        $router->apiResource('local-authorizers', \App\Http\Controllers\OpenPlatform\LocalAuthorizerController::class)->only([
            'index', 'store', 'show', 'destroy'
        ]);
        $router->apiResource('beta-mini-program',\App\Http\Controllers\OpenPlatform\BetaMiniProgramController::class)->only('store');
        $router->apiResource('code', \App\Http\Controllers\OpenPlatform\CodeController::class)->only(['index', 'update', 'destroy']);


        /** 开放平台代小程序实现功能 */
        $router->group([
            'prefix' => 'mp/{appId}',
        ], function (\Illuminate\Routing\Router $router) {
            $router->get('basic-info', [\App\Http\Controllers\SubMiniProgramController::class, 'basicInfo']);
            $router->post('delete-local-media',
                [\App\Http\Controllers\SubMiniProgramController::class, 'deleteLocalMedia']);
            $router->post('local-media', [\App\Http\Controllers\SubMiniProgramController::class, 'uploadLocalMedia']);
            $router->get('local-media', [\App\Http\Controllers\SubMiniProgramController::class, 'getLocalMediaList']);
            $router->get('local-media/{fileName}',
                [\App\Http\Controllers\SubMiniProgramController::class, 'getLocalMedia']);
            $router->post('upload-template-media',
                [\App\Http\Controllers\SubMiniProgramController::class, 'uploadTemplateMedia']);
            $router->get('testers', [\App\Http\Controllers\SubMiniProgramController::class, 'testers']);
            $router->post('testers', [\App\Http\Controllers\SubMiniProgramController::class, 'bindTester']);
            $router->delete('testers/{userSlug}',
                [\App\Http\Controllers\SubMiniProgramController::class, 'unBindTester']);

            $router->post('check-nick-name', [\App\Http\Controllers\SubMiniProgramController::class, 'checkNickName']);
            $router->get('nick-name-audit-status/{auditId}',
                [\App\Http\Controllers\SubMiniProgramController::class, 'getNicknameAuditStatus']);
            $router->put('nick-name', [\App\Http\Controllers\SubMiniProgramController::class, 'setNickName']);
            $router->put('avatar', [\App\Http\Controllers\SubMiniProgramController::class, 'setAvatar']);
            $router->put('signature', [\App\Http\Controllers\SubMiniProgramController::class, 'setSignature']);

            $router->get('get-server-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'getServerDomain']);
            $router->post('add-server-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'addServerDomain']);
            $router->post('set-server-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'setServerDomain']);
            $router->post('delete-server-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'deleteServerDomain']);

            $router->get('get-web-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'getWebDomain']);
            $router->post('sync-web-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'syncWebDomain']);
            $router->post('add-web-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'addWebDomain']);
            $router->post('set-web-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'setWebDomain']);
            $router->post('delete-web-domain', [\App\Http\Controllers\SubMiniProgramController::class, 'deleteWebDomain']);

            $router->post('code-commit', [\App\Http\Controllers\SubMiniProgramController::class, 'codeCommit']);
            $router->get('code-pages', [\App\Http\Controllers\SubMiniProgramController::class, 'getCodePage']);
            $router->post('code-audit', [\App\Http\Controllers\SubMiniProgramController::class, 'codeAudit']);
            $router->post('upload-code-audit-media', [\App\Http\Controllers\SubMiniProgramController::class, 'uploadCodeAuditMedia']);
            $router->get('code-audit-latest-status', [\App\Http\Controllers\SubMiniProgramController::class, 'getLatestAuditStatus']);
            $router->get('code-audit-status', [\App\Http\Controllers\SubMiniProgramController::class, 'getAuditStatus']);
            $router->post('code-audit-withdraw', [\App\Http\Controllers\SubMiniProgramController::class, 'withdrawAudit']);
            $router->post('code-release', [\App\Http\Controllers\SubMiniProgramController::class, 'codeRelease']);
            $router->post('code-rollback-release', [\App\Http\Controllers\SubMiniProgramController::class, 'codeRollbackRelease']);
            $router->post('code-release-histories', [\App\Http\Controllers\SubMiniProgramController::class, 'getCodeReleaseHistories']);

            $router->get('privacy-setting', [\App\Http\Controllers\SubMiniProgramController::class, 'getPrivacySetting']);
            $router->post('privacy-setting', [\App\Http\Controllers\SubMiniProgramController::class, 'setPrivacySetting']);
            $router->post('upload-privacy-file', [\App\Http\Controllers\SubMiniProgramController::class, 'uploadPrivacyExtFile']);

            $router->get('category', [\App\Http\Controllers\SubMiniProgramController::class, 'getCategory']);
            $router->get('all-categories', [\App\Http\Controllers\SubMiniProgramController::class, 'getAllCategories']);
            $router->get('categories-by-type', [\App\Http\Controllers\SubMiniProgramController::class, 'getAllCategoriesByType']);
            $router->post('category', [\App\Http\Controllers\SubMiniProgramController::class, 'addCategory']);
            $router->post('delete-category', [\App\Http\Controllers\SubMiniProgramController::class, 'deleteCategory']);
        });

        /** 开放平台代公众号实现功能 */
        $router->group([
            'prefix' => 'oa/{appId}',
        ], function (\Illuminate\Routing\Router $router) {

        });
    });
});
