<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Services\PlatformService;
use Closure;
use Illuminate\Http\Request;

class OfficialAccountMiddleware
{
    /**
     * @var PlatformService
     */
    private $platformServe;
    public function __construct()
    {
        $this->platformServe = app(PlatformService::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws ParamsErrorException
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var string $sign 该平台的标识码 */
        $slug = request()->route('oaSlug');
        /** @var int $id */
        $id = request()->route('oaId');
        $platform = null;
        if ($slug) {
            $platform = $this->platformServe->getOfficialAccountBySign($slug);
        } else if ($id) {
            $platform = $this->platformServe->getOfficialAccountById($id);
        }
        if (!$platform) {
            throw new ParamsErrorException('平台参数异常');
        }
        $request->attributes->add(['officialAccount' => $platform]);
        return $next($request);
    }
}
