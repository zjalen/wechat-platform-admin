<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(\route('frontend'));
    //    return view('welcome');
    //    return response()->json(['data' => '欢迎使用'])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
});

Route::get('/frontend', function () {
    return view('frontend');
})->name('frontend');

/** 开放平台授权页面 */
Route::group([
    'prefix' => 'open-platform/{openPlatformSlug}',
    'middleware' => ['platform.op']
], function (\Illuminate\Routing\Router $router) {
    // 绑定开放平台页面
    $router->get('bind', [\App\Http\Controllers\OpenPlatform\OpenPlatformController::class, 'bind'])->name('bind');
    // 绑定开放平台回调页面
    $router->get('bind-callback',
        [\App\Http\Controllers\OpenPlatform\OpenPlatformController::class, 'bindCallback'])->name('bindCallback');
    // 快速复用公众号注册小程序
    $router->get('fast-create-mp-auth', [\App\Http\Controllers\OpenPlatform\OpenPlatformController::class, 'fastCreateMpAuth'])->name('fastCreateMpAuth');
    // 快速复用公众号认证信息注册小程序授权回调
    $router->get('fast-create-mp-auth-callback/{appId}',
        [\App\Http\Controllers\OpenPlatform\OpenPlatformController::class, 'fastCreateMiniProgramAuthCallback'])->name('fastCreateMiniProgramAuthCallback');
});
