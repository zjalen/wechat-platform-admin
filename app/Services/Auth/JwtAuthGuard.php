<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/5/19 11:33
 */
class JwtAuthGuard implements Guard
{
    /**
     * @var Authenticatable
     */
    private $user;

    /**
     * @var JwtService
     */
    private $jwtService;

    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        $token = request()->bearerToken();
        if (!$token || $token == 'null') {
            return false;
        }
        $user = $this->jwtService->getJwtPayload($token);
        if (!$user || $user->is_forbidden) {
            return false;
        }
        $this->setUser($user);
        return true;
    }

    /**
     * @return bool
     */
    public function guest(): bool
    {
        return $this->id() == null;
    }

    /**
     * @return Authenticatable|null
     */
    public function user(): ?Authenticatable
    {
        return $this->user;
    }

    /**
     * @return int|string|null
     */
    public function id()
    {
        return $this->user->getAuthIdentifier();
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        $email = $credentials['email'];
        $password = $credentials['password'];
        $user = User::query()->where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }
        $this->setUser($user);
        Auth::setUser($user);
        return true;
    }

    /**
     * @param Authenticatable $user
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}
