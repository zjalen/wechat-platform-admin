<?php

namespace App\Providers;

use App\Services\Auth\JwtAuthGuard;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 拓展自定义一个认证守卫 jwt guard，jwt 对应 config/auth.php 中 driver
        app(AuthManager::class)->extend('jwt', function ($app, $name, array $config) {
            return new JwtAuthGuard();
        });
    }
}
