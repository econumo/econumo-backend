<?php

declare(strict_types=1);

namespace App\UI\Service\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResponseFactory
{
    public static function createOkResponse(
        Request $request,
        mixed $data = [],
        string $message = '',
        int $httpCode = Response::HTTP_OK
    ): Response {
        return static::createJsonResponse([
            'message' => $message,
            'data' => $data,
        ], $httpCode);
    }

    public static function createErrorResponse(
        Request $request,
        string $message = '',
        int $code = 0,
        array $errors = [],
        int $httpCode = Response::HTTP_BAD_REQUEST
    ): Response {
        return static::createJsonResponse([
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
        ], $httpCode);
    }

    public static function createExceptionResponse(
        Request $request,
        string $message,
        int $code = 0,
        ?Throwable $exception = null,
        int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): Response {
        $data = [
            'message' => $message,
            'code' => $code
        ];
        if (null !== $exception) {
            $data['exceptionType'] = $exception::class;
            $data['stackTrace'] = $exception->getTrace();
        }

        return static::createJsonResponse($data, $httpCode);
    }

    protected static function createJsonResponse(
        mixed $data,
        int $httpCode
    ): JsonResponse {
        return new JsonResponse($data, $httpCode);
    }
}
