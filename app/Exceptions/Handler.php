<?php

namespace App\Exceptions;

use App\Exceptions\BusinessExceptions\BaseBusinessException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        BaseBusinessException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e) {
            if ($e instanceof SignatureInvalidException) {
                return $this->jsonResponse('token 异常', 40101, 401);
            } else {
                if ($e instanceof ExpiredException) {
                    return $this->jsonResponse('token 过期', 40104, 401);
                } else {
                    if ($e instanceof BaseBusinessException) {
                        return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getStatusCode());
                    } else {
                        if ($e instanceof ValidationException) {
                            return $this->jsonResponse($e->validator->getMessageBag()->first(),
                                $e->status, $e->status);
                        } else {
                            if ($e instanceof HttpExceptionInterface) {
                                $statusCode = $this->isHttpException($e) ? $e->getStatusCode() : 500;
                                $code = $e->getCode() ?: $statusCode;
                            } else if ( $e instanceof \EasyWeChat\Kernel\Exceptions\HttpException) {
                                return $this->jsonResponse($e->getMessage(),
                                    $e->getCode(), 400);
                            } else {
                                $statusCode = 500;
                                $code = 500;
                            }
                        }
                    }
                }
            }

            $errMsg = ApiStatusMessage::getMessageByBusinessCode($code) ?:
                ApiStatusMessage::getMessageByHttpStatusCode($statusCode);
            return $this->jsonResponse($errMsg, $code, $statusCode);
        });
    }

    public function jsonResponse(string $errMsg, int $code = 400, int $httpCode = 400): \Illuminate\Http\JsonResponse
    {
        $content = [
            'errCode' => $code,
            'errMsg' => $errMsg
        ];
        return response()->json($content, $httpCode);
    }
}
