<?php


namespace App\Exceptions\BusinessExceptions;


class NotFoundException extends BaseBusinessException
{
    /**
     * 未找到相应结果
     * NotFoundException constructor.
     * @param ?string $message 异常信息
     * @param int $code 异常码
     * @param int $statusCode http 状态码
     */
    public function __construct(?string $message = '未找到对应信息', int $code = 404, int $statusCode = 404)
    {
        parent::__construct($message, $code, $statusCode);
    }
}
