<?php
namespace App\Exceptions\BusinessExceptions;


/**
 * 请求失败异常类
 * Class UnavailableException
 * @package App\Exceptions\BusinessExceptions
 */
class UnavailableException extends BaseBusinessException
{
    /**
     * 请求失败
     * UnavailableException constructor.
     * @param ?string $message
     * @param int $code
     * @param int $statusCode
     */
    public function __construct(?string $message = '请求失败', int $code = 400, int $statusCode = 400)
    {
        parent::__construct($message, $code, $statusCode);
    }
}
