<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormatJsonResponse
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return JsonResponse|object|StreamedResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $statusCode = 200;
        $content = [];
        if ($response instanceof StreamedResponse) {
            return $response;
        }else if ($response instanceof JsonResponse) {
            $data = $response->getData();
            $data = json_decode(json_encode($data), true);
            if (array_key_exists('errcode', $data)) {
                /** 微信端返回的 http 请求结果 */
                if ($data['errcode'] != 0) {
                    $statusCode = 400;
                }
                $content['errMsg'] = $data['errmsg'];
                $content['errCode'] = $data['errcode'];
                $content['data'] = $data;
            } else if (array_key_exists('exceptionCode', $data)) {
                /** 异常格式化结果 */
                $content = [
                    'errCode' => $data['exceptionCode'],
                    'errMsg' => $data['exceptionMsg']
                ];
                $statusCode = $response->getStatusCode();
            } else {
                /** 后台方法直接处理结果 */
                $content['errCode'] = 0;
                $content['data'] = $data;
            }
        } else {
            $content = [
                'errCode' => 0,
                'data' => $response
            ];
        }
        return response()->json($content)->setStatusCode($statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
