<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Models\Platform;
use App\Services\PlatformService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyPlatformOpenState
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
     * @param  string  $type
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws ParamsErrorException
     */
    public function handle(Request $request, Closure $next, string $type)
    {
        /** @var string $sign 该平台的标识码 */
        $slug = request()->route('slug');
        if (!$slug) {
            throw new ParamsErrorException('平台参数异常');
        }
        Log::info($type);
        switch ($type) {
            case 'op':
                $typeInt = Platform::TYPE_THIRD_PARTY;
                break;
            case 'oa':
                $typeInt = Platform::TYPE_OFFICIAL_ACCOUNT;
                break;
            default:
                throw new ParamsErrorException('平台参数异常');
        }
        $platform = $this->platformServe->getPlatformBySlugAndType($slug, $typeInt);
        if (!$platform || !$platform->is_open) {
            throw new ParamsErrorException('平台参数异常');
        }
        switch ($platform->type) {
            case Platform::TYPE_THIRD_PARTY:
                $request->attributes->add(['openPlatform' => $platform]);
                break;
            case Platform::TYPE_OFFICIAL_ACCOUNT:
                $request->attributes->add(['officialAccountModel' => $platform]);
                break;
            default:
                break;
        }
        return $next($request);
    }
}
