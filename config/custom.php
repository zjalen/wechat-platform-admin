<?php
return [
    'jwt' => [
        'secret' => env('JWT_SECRET', 'JWT_SECRET'),
        'ttl' => env('JWT_TTL', 60 * 24 * 10), // 单位分钟，默认 10 天
        'algo' => env('JWT_ALGO', 'HS256')
    ]
];
