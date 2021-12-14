<?php
namespace App\Exceptions\BusinessExceptions;


/**
 * 服务不可用异常类
 * Class UnavailableException
 * @package App\Exceptions\BusinessExceptions
 */
class UnavailableException extends BaseBusinessException
{
    /**
     * 服务不可用
     * UnavailableException constructor.
     * @param ?string $message
     * @param int $code
     * @param int $statusCode
     */
    public function __construct(?string $message = '服务不可用', int $code = 400, int $statusCode = 400)
    {
        parent::__construct($message, $code, $statusCode);
    }
}
