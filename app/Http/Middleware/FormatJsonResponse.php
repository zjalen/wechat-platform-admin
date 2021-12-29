<?php

namespace App\Http\Middleware;

use App\Exceptions\JsonExceptionResponse;
use App\Models\OperationLog;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormatJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $response = $next($request);
        $statusCode = 200;
        $content = [];
        if ($response instanceof JsonResponse) {
            $data = $response->getData();
            $data = json_decode(json_encode($data), true);
            if (array_key_exists('errcode', $data)) {
                /** 微信端返回的 http 请求结果 */
                if ($data['errcode'] != 0) {
                    $statusCode = 400;
                }
                $content['errMsg'] = $data['errmsg'];
                $content['errCode'] = $data['errcode'];
            } else if (array_key_exists('exceptionCode', $data)) {
                /** 异常格式化结果 */
                $content = [
                    'errCode' => $data['exceptionCode'],
                    'errMsg' => $data['exceptionMsg']
                ];
            } else {
                /** 后台方法直接处理结果 */
                $content['errCode'] = 0;
            }
            $content['data'] = $data;
        } else {
            $content = [
                'errCode' => 0,
                'data' => $response
            ];
        }
        return response()->json($content)->setStatusCode($statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
