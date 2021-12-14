<?php

namespace App\Exceptions\BusinessExceptions;


/**
 * Notes:
 * User: jalen
 * DateTime: 2021/4/16 18:11
 */
class NotAllowedException extends BaseBusinessException
{
    /**
     * 不允许访问
     * NotAllowedException constructor.
     * @param ?string $message 异常信息
     * @param int $code 异常码
     * @param int $statusCode http 状态码
     */
    public function __construct(?string $message = '您无权访问', int $code = 403, int $statusCode = 403)
    {
        parent::__construct($message, $code, $statusCode);
    }
}
