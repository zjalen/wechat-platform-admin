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
class AuthController extends Controller
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(): array
    {
        $this->validate(request(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = request()->only('email', 'password');
        if (!auth()->validate($credentials)) {
            throw new UnauthorizedException('账号或密码错误');
        }
        return ['data' => $this->jwtService->generateJwtToken(Auth::id())];
    }
}
