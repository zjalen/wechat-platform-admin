<?php


namespace App\Exceptions\BusinessExceptions;


/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/5/18 15:23
 */
class UnauthorizedException extends BaseBusinessException
{
    /**
     * 认证失败异常
     * ParamsErrorException constructor.
     * @param ?string $message 异常信息
     * @param int $code 异常码
     * @param int $statusCode http 状态码
     */
    public function __construct(?string $message = '身份认证失败', int $code = 401, int $statusCode = 401)
    {
        parent::__construct($message, $code, $statusCode);
    }
}
