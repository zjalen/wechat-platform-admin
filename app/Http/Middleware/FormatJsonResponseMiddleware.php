<?php


namespace App\Http\Middleware;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/7/15 15:21
 */
class FormatJsonResponseMiddleware
{

    public function handle(Request $request, \Closure $next): \Illuminate\Http\JsonResponse
    {
        $response = $next($request);
        if ($response instanceof JsonResponse) {
            return $response;
        } else if ($response instanceof Response) {
            $content = $response->getOriginalContent();
        } else {
            $content = $response;
        }
        $content = [
            'code' => 0,
            'data' => $content
        ];
        return response()->json($content)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
