<?php


namespace App\Services\Auth;


use App\Models\User;
use Firebase\JWT\JWT;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/5/19 09:51
 */
class JwtService
{
    /**
     * 通过用户信息获得 jwt token
     * @param User $user
     * @return string
     */
    public function getJwtTokenByUser(User $user): string
    {
        return $this->generateJwtToken($user->id);
    }

    /**
     * 获取 jwt token
     * @param int id
     * @return string
     */
    public function generateJwtToken(int $id): string
    {
        $key = config('custom.jwt.secret');
        $payload = new JwtModel();
        $payload->sub = $id;
        $payload->iss = config('app.url');
        $payload->iat = time();
        $payload->exp = time() + config('custom.jwt.ttl')  * 60;
        return JWT::encode($payload, $key, env('JWT_ALGO'));
    }

    /**
     * 解析 jwt payload 信息
     * @param  string  $token
     * @return User|null
     */
    public function getJwtPayload(string $token): ?User
    {
        if (str_starts_with($token,'Bearer ')) {
            $token = explode('Bearer ', $token)[1];
        }
        $key = config('custom.jwt.secret');
        try {
            /** @var JwtModel $jwt */
            $jwt = JWT::decode($token, $key, [config('custom.jwt.algo')]);
        } catch (\Exception $exception) {
            return null;
        }

        if ($jwt->exp < time()) {
            return null;
        }

        return User::query()->find($jwt->sub);
    }
}
