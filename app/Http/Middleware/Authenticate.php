<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessExceptions\UnauthorizedException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * 该项目为前后端分离，因此不做 redirect login 操作，直接返回异常结果
     * @throws UnauthorizedException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new UnauthorizedException('身份校验失败', 40100);
    }
}
