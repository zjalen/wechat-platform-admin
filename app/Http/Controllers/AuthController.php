<?php


namespace App\Http\Controllers;


use App\Exceptions\BusinessExceptions\UnauthorizedException;
use App\Services\Auth\JwtService;
use Illuminate\Support\Facades\Auth;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/13 17:26
 */
class AuthController extends BaseController
{
    /**
     * @var JwtService
     */
    private $jwtService;

    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    /**
     * @throws UnauthorizedException
     */
    public function login(): string
    {
        $credentials = request()->only('email', 'password');

        if (!auth()->validate($credentials)) {
            throw new UnauthorizedException('账号或密码错误');
        }
        return $this->jwtService->generateJwtToken(Auth::id());
    }
}
