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
/** 公众平台对微信官方接口，无需认证身份 */
Route::group([
    'prefix' => 'official-account-server/{officialAccountSlug}',
    'middleware' => ['platform.oa']
], function (\Illuminate\Routing\Router $router) {
    $router->get('notify',
        [\App\Http\Controllers\OfficialAccount\NotifyController::class, 'store'])->name('officialAccountNotifyValidate');
    $router->post('notify',
        [\App\Http\Controllers\OfficialAccount\NotifyController::class, 'store'])->name('officialAccountNotify');
});

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

/** 开放平台对外提供的第三方使用接口 */
Route::group([
    'prefix' => 'open-api/{slug}',
    'middleware' => ['platform.open-state:op']
], function (\Illuminate\Routing\Router $router) {
    /** 对外开放代公众号实现接口 */
    $router->group([
        'prefix' => 'wx-oa/{appId}',
    ], function (\Illuminate\Routing\Router $router) {
        $router->get('getUserInfo', [\App\Http\Controllers\OpenPlatform\OfficialAccount\OpenApiController::class, 'getUserInfo']);
    });
    /** 对外开放代小程序实现接口 */
    $router->group([
        'prefix' => 'wx-mp/{appId}',
    ], function (\Illuminate\Routing\Router $router) {
        $router->get('getUserInfo', [\App\Http\Controllers\OpenPlatform\MiniProgram\OpenApiController::class, 'getUserInfo']);
    });
});

Route::group([
    /** 统一格式化结果 */
    'middleware' => ['format.json', 'operation-log'],
], function (\Illuminate\Routing\Router $router) {
    /** 登录 */
    $router->post('login', [\App\Http\Controllers\AuthController::class, 'login']);

    // 对外登录接口，后续独立设置权限
    $router->post('open-platform/{openPlatformId}/mp/{appId}/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);

    /** 资源文件查看 */
    $router->get('platforms/{appId}/resources/{token}', [\App\Http\Controllers\ResourceController::class, 'show'])->name('resources.show');

    /** 首页平台列表相关操作 */
    $router->group(['middleware' => ['auth:api']], function (\Illuminate\Routing\Router $router) {
        $router->apiResource('platforms', \App\Http\Controllers\PlatformController::class);
        $router->apiResource('platforms/{appId}/resources', \App\Http\Controllers\ResourceController::class)->only(['index','store']);
        $router->post('platforms/{appId}/resources/delete', [\App\Http\Controllers\ResourceController::class, 'destroy']);
        $router->apiResource('operation-logs', \App\Http\Controllers\OperationLogController::class)->only('index');
    });

    /** 公众平台相关 */
    $router->group([
        'prefix' => 'official-account/{officialAccountId}',
        'middleware' => ['auth:api', 'platform.oa']
    ], function (\Illuminate\Routing\Router $router) {
        $router->get('', [\App\Http\Controllers\OfficialAccount\OfficialAccountController::class, 'show']);
        $router->put('', [\App\Http\Controllers\OfficialAccount\OfficialAccountController::class, 'updateAutoReplyConfig']);
        $router->apiResource('custom-menu', \App\Http\Controllers\OfficialAccount\CustomMenuController::class)->only(['index', 'show', 'store', 'destroy']);
        $router->get('custom-menu/published/{menu_id}', [\App\Http\Controllers\OfficialAccount\CustomMenuController::class, 'showPublishedMenu']);
        $router->post('custom-menu/published', [\App\Http\Controllers\OfficialAccount\CustomMenuController::class, 'publish']);
        $router->delete('custom-menu/published/{menu_id}', [\App\Http\Controllers\OfficialAccount\CustomMenuController::class, 'destroyPublishedMenu']);
        $router->apiResource('auto-reply-rules', \App\Http\Controllers\OfficialAccount\AutoReplyRuleController::class);
        $router->get('materials-count', [\App\Http\Controllers\OfficialAccount\MaterialController::class, 'count']);
        $router->apiResource('materials', \App\Http\Controllers\OfficialAccount\MaterialController::class)->only(['index', 'show', 'store', 'destroy']);
        $router->apiResource('drafts', \App\Http\Controllers\OfficialAccount\DraftController::class);
        $router->apiResource('articles', \App\Http\Controllers\OfficialAccount\ArticleController::class)->only(['index', 'store', 'show', 'destroy']);
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
            $router->get('basic-info', [\App\Http\Controllers\OpenPlatform\MiniProgram\MiniProgramController::class, 'show']);
            $router->post('upload-temporary-media',
                [\App\Http\Controllers\OpenPlatform\MiniProgram\MiniProgramController::class, 'uploadTemporaryMedia']);
            $router->apiResource('testers', \App\Http\Controllers\OpenPlatform\MiniProgram\TesterController::class)->only(['index', 'store', 'destroy']);
            $router->apiResource('nick-name', \App\Http\Controllers\OpenPlatform\MiniProgram\NickNameController::class)->only(['show', 'update']);
            $router->get('nick-name/audit-status/{audit_id}', [\App\Http\Controllers\OpenPlatform\MiniProgram\NickNameController::class, 'auditStatus']);
            $router->apiResource('avatar', \App\Http\Controllers\OpenPlatform\MiniProgram\AvatarController::class)->only(['update']);
            $router->apiResource('signature', \App\Http\Controllers\OpenPlatform\MiniProgram\SignatureController::class)->only(['update']);
            $router->apiResource('server-domain', \App\Http\Controllers\OpenPlatform\MiniProgram\ServerDomainController::class)->only(['index','update']);
            $router->apiResource('webview-domain', \App\Http\Controllers\OpenPlatform\MiniProgram\WebviewDomainController::class)->only(['index', 'update']);
            $router->apiResource('privacy-setting', \App\Http\Controllers\OpenPlatform\MiniProgram\PrivacySettingController::class)->only(['index', 'store']);
            $router->post('upload-privacy-ext-file', [\App\Http\Controllers\OpenPlatform\MiniProgram\PrivacySettingController::class, 'uploadPrivacyExtFile']);
            $router->apiResource('categories', \App\Http\Controllers\OpenPlatform\MiniProgram\CategoryController::class)->only(['index','store','show','destroy']);
            $router->apiResource('beta-verification', \App\Http\Controllers\OpenPlatform\MiniProgram\BetaVerificationController::class)->only(['store']);
            $router->apiResource('qr', \App\Http\Controllers\OpenPlatform\MiniProgram\QRController::class)->only(['store']);

            $router->post('code/commit', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'commit']);
            $router->get('code/test-qr', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'getQrCode']);
            $router->get('code/pages', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'getPage']);
            $router->post('code/audit', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'audit']);
            $router->post('code/upload-audit-media', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'uploadAuditMedia']);
            $router->get('code/audit-status', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'getAuditStatus']);
            $router->post('code/audit-withdraw', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'withdrawAudit']);
            $router->post('code/release', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'release']);
            $router->post('code/revert', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'revertReleaseVersion']);
            $router->get('code/release-history-versions', [ \App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'getReleaseHistoryVersions']);
            $router->get('code/gray-release', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'getGrayRelease']);
            $router->post('code/gray-release', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'grayRelease']);
            $router->delete('code/gray-release', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'revertGrayRelease']);
            $router->get('code/speed-audit', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'getSpeedAuditCount']);
            $router->post('code/speed-audit', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'speedAudit']);
            $router->get('code/support-version', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'getSupportVersion']);
            $router->post('code/support-version', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'setSupportVersion']);
            $router->post('code/visit-status', [\App\Http\Controllers\OpenPlatform\MiniProgram\CodeController::class, 'changeVisitStatus']);

            // 对外使用接口
            $router->post('auth/user/login', [\App\Http\Controllers\OpenPlatform\MiniProgram\AuthController::class, 'store']);
        });

        /** 开放平台代公众号实现功能 */
        $router->group([
            'prefix' => 'oa/{appId}',
        ], function (\Illuminate\Routing\Router $router) {
            $router->put('', [\App\Http\Controllers\OpenPlatform\OfficialAccount\OfficialAccountController::class, 'updateAutoReplyConfig']);
            $router->apiResource('custom-menu', \App\Http\Controllers\OpenPlatform\OfficialAccount\CustomMenuController::class)->only(['index', 'show', 'store', 'destroy']);
            $router->get('custom-menu/published/{menu_id}', [\App\Http\Controllers\OpenPlatform\OfficialAccount\CustomMenuController::class, 'showPublishedMenu']);
            $router->post('custom-menu/published', [\App\Http\Controllers\OpenPlatform\OfficialAccount\CustomMenuController::class, 'publish']);
            $router->delete('custom-menu/published/{menu_id}', [\App\Http\Controllers\OpenPlatform\OfficialAccount\CustomMenuController::class, 'destroyPublishedMenu']);
            $router->apiResource('auto-reply-rules', \App\Http\Controllers\OpenPlatform\OfficialAccount\AutoReplyRuleController::class);
            $router->get('materials-count', [\App\Http\Controllers\OpenPlatform\OfficialAccount\MaterialController::class, 'count']);
            $router->apiResource('materials', \App\Http\Controllers\OpenPlatform\OfficialAccount\MaterialController::class)->only(['index', 'show', 'store', 'destroy']);
            $router->apiResource('drafts', \App\Http\Controllers\OpenPlatform\OfficialAccount\DraftController::class);
            $router->apiResource('articles', \App\Http\Controllers\OpenPlatform\OfficialAccount\ArticleController::class)->only(['index', 'store', 'show', 'destroy']);
        });
    });
});
