<?php

namespace App\Exceptions\BusinessExceptions;


use Exception;

abstract class BaseBusinessException extends Exception
{
    /**
     * @var int
     */
    private $statusCode;

    public function __construct(?string $message, int $code, int $statusCode = 400)
    {
        parent::__construct($message, $code);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
