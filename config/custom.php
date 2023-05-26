<?php
return [
    'jwt' => [
        'secret' => env('JWT_SECRET', 'JWT_SECRET'),
        'ttl' => env('JWT_TTL', 60 * 24 * 10), // 单位分钟，默认 10 天
        'algo' => env('JWT_ALGO', 'HS256')
    ],
    'media_token_cache_ttl' => env('MEDIA_TOKEN_CACHE_TTL', 60), // 图片可访问 token 时间，单位分钟
    'media_token_cache_prefix' => env('MEDIA_TOKEN_CACHE_PREFIX', 'media-token:'),
    'enable_option_log' => env('ENABLE_OPERATION_LOG', false),
    'option_log_method' => explode('|', env('OPERATION_LOG_METHOD', '')),
    'open_api_token' => env('OPEN_API_TOKEN')
];
