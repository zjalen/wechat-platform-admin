<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessExceptions\NotAllowedException;
use Closure;
use Illuminate\Http\Request;

class VerifyMediaToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->route('token');
        $appId = $request->route('appId');
        $key = config('custom.media_token_cache_prefix').$appId;
        if (cache()->get($key) != $token) {
            throw new NotAllowedException('token 无效');
        }
        return $next($request);
    }
}
