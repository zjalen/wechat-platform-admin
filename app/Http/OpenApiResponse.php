<?php


namespace App\Http;


use Illuminate\Http\JsonResponse;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/5/17 15:17
 */
trait OpenApiResponse
{
    public static function success($data = null, $code = 1, $statusCode = 200): JsonResponse
    {
        $content = [
            'code' => $code,
            'data' => $data
        ];
        $statusCode = $statusCode ?: $code;
        return response()->json($content, $statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public static function fail($errMsg = null, $code = 400, $statusCode = 400): JsonResponse
    {
        $content = [
            'code' => $code,
            'errMsg' => $errMsg
        ];
        $statusCode = $statusCode ?: $code;
        return response()->json($content, $statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
