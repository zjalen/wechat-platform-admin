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
    $router->post('serve',
        [\App\Http\Controllers\OpenPlatformServerController::class, 'serve'])->name('openPlatformServe');
    // 代公众平台接收用户消息
    $router->post('{appId}/notify',
        [\App\Http\Controllers\OpenPlatformServerController::class, 'subPlatformNotify'])->name('subPlatformNotify');
});

/** 无需 json 格式化的文件下载 */
Route::get('open-platform/{openPlatformId}/mp/{appId}/code-test-qr', [\App\Http\Controllers\SubMiniProgramController::class, 'getTestQRCode'])->middleware(['auth:api', 'platform.op']);

Route::group([
    /** 统一格式化结果 */
    'middleware' => ['format.json', 'operation-log'],
], function () {
    /** 登录 */
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

    /** 首页平台列表相关操作 */
    Route::group(['middleware' => ['auth:api']], function (\Illuminate\Routing\Router $router) {
        $router->apiResource('platforms', \App\Http\Controllers\PlatformController::class);
        $router->apiResource('operation-logs', \App\Http\Controllers\OperationLogController::class)->only('index');
    });

    /** 开放平台相关 */
    Route::group([
        'prefix' => 'open-platform/{openPlatformId}',
        'middleware' => ['auth:api', 'platform.op']
    ], function (\Illuminate\Routing\Router $router) {
        $router->get('secret-config', [\App\Http\Controllers\OpenPlatformController::class, 'getSecretConfig']);
        $router->get('get-server-domain', [\App\Http\Controllers\OpenPlatformController::class, 'getServerDomain']);
        $router->post('delete-server-domain', [\App\Http\Controllers\OpenPlatformController::class, 'deleteServerDomain']);
        $router->post('add-server-domain', [\App\Http\Controllers\OpenPlatformController::class, 'addServerDomain']);
        $router->post('set-server-domain', [\App\Http\Controllers\OpenPlatformController::class, 'setServerDomain']);
        $router->post('get-domain-confirm-file', [\App\Http\Controllers\OpenPlatformController::class, 'getDomainConfirmFile']);
        $router->get('get-web-domain', [\App\Http\Controllers\OpenPlatformController::class, 'getWebDomain']);
        $router->post('delete-web-domain', [\App\Http\Controllers\OpenPlatformController::class, 'deleteWebDomain']);
        $router->post('add-web-domain', [\App\Http\Controllers\OpenPlatformController::class, 'addWebDomain']);
        $router->post('set-web-domain', [\App\Http\Controllers\OpenPlatformController::class, 'setWebDomain']);
        $router->get('authorizers', [\App\Http\Controllers\OpenPlatformController::class, 'getAuthorizerList']);
        $router->get('authorizers/{appId}', [\App\Http\Controllers\OpenPlatformController::class, 'getAuthorizer']);
        $router->post('beta-mini-program',
            [\App\Http\Controllers\OpenPlatformController::class, 'createBetaMiniProgram']);

        $router->get('code-drafts', [\App\Http\Controllers\OpenPlatformController::class, 'codeDrafts']);
        $router->get('code-template', [\App\Http\Controllers\OpenPlatformController::class, 'codeTemplate']);
        $router->post('add-template', [\App\Http\Controllers\OpenPlatformController::class, 'addCodeDraftToTemplate']);
        $router->post('delete-template', [\App\Http\Controllers\OpenPlatformController::class, 'deleteCodeTemplate']);

        /** 存储已授权的子平台详细信息在数据库方便后续查看 */
        $router->apiResource('sub-platforms', \App\Http\Controllers\SubPlatformController::class)->only([
            'index', 'store', 'show', 'destroy'
        ]);

        /** 开放平台代小程序实现功能 */
        Route::group([
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
        Route::group([
            'prefix' => 'oa/{appId}',
        ], function (\Illuminate\Routing\Router $router) {

        });
    });
});
