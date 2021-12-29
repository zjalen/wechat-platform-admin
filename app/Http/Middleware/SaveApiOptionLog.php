<?php

namespace App\Http\Middleware;

use App\Models\OperationLog;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveApiOptionLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (config('custom.enable_option_log')) {
            $this->saveOperationLog($request, $response);
        }
        return $response;
    }

    public function saveOperationLog(Request $request, $response)
    {
        if (!in_array($request->method(), config('custom.option_log_method'))) {
            return;
        }
        if ($request->path() == 'api/operation-logs') {
            return;
        }
        $wxResponse = [];
        if ($response instanceof JsonResponse) {
            $data = $response->getData();
            $data = json_decode(json_encode($data), true);
            if (array_key_exists('errcode', $data)) {
                $wxResponse = $data;
            }
        }
        $user_id = 0;
        if(Auth::check()) {
            $user_id = (int) Auth::id();
        }
        $input = $request->all();
        $log = new OperationLog(); # 提前创建表、model
        $log->user_id = $user_id;
        $log->path = $request->path();
        $log->method = $request->method();
        $log->ip = $request->ip();
        $log->params = $input;
        $log->wx_result = $wxResponse;
        $log->save();
    }
}
