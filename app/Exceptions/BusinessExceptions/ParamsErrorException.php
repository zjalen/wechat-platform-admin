<?php


namespace App\Exceptions\BusinessExceptions;

/**
 * 参数异常
 * Class ParamsErrorException
 * @package App\Exceptions\BusinessExceptions
 */
class ParamsErrorException extends BaseBusinessException
{
    /**
     * 参数异常
     * ParamsErrorException constructor.
     * @param ?string $message 异常信息
     * @param int $code 异常码
     * @param int $statusCode http 状态码
     */
    public function __construct(?string $message = '请求参数异常', int $code = 419, int $statusCode = 419)
    {
        parent::__construct($message, $code, $statusCode);
    }
}
