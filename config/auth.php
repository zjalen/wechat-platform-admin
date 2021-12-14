<?php

return [
    // 这里是指定默认的看守器
    // web 的意思取下面 guards 数组 key 为 web 的那个
    // passwords 是重置密码相关，暂时不使用
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    // 这里定义可以用的 guard
    // driver 指的就是上面的对 Guard 契约的具体实现那个类了
    // users 是下面 providers 数组 key 为 users 的那个
    'guards' => [
        'web' => [
            'driver' => 'session',    // SessionGuard 实现
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt',  // JWTGuard 实现，AuthServiceProvider 中自定义 guard
            'provider' => 'users',
        ],
    ],

    // 这个的作用是指定认证所需的 user 来源的数据表
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class,
        ]
    ]
];
