<?php

declare(strict_types=1);

namespace App\UI\Service\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResponseFactory
{
    /**
     * @param Request $request
     * @param string $message
     * @param mixed $data
     * @param int $httpCode
     * @return Response
     */
    public static function createOkResponse(
        Request $request,
        $data = [],
        string $message = '',
        int $httpCode = Response::HTTP_OK
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $response = static::createJsonResponse([
                    'message' => $message,
                    'data' => $data,
                ], $httpCode);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param string $message
     * @param int $code
     * @param array $errors
     * @param int $httpCode
     * @return Response
     */
    public static function createErrorResponse(
        Request $request,
        string $message = '',
        int $code = 0,
        array $errors = [],
        int $httpCode = Response::HTTP_BAD_REQUEST
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $response = static::createJsonResponse([
                    'message' => $message,
                    'code' => $code,
                    'errors' => $errors,
                ], $httpCode);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param string $message
     * @param int $code
     * @param Throwable $exception
     * @param int $httpCode
     * @return Response
     */
    public static function createExceptionResponse(
        Request $request,
        string $message,
        int $code = 0,
        ?Throwable $exception = null,
        int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $data = [
                    'message' => $message,
                    'code' => $code
                ];
                if (null !== $exception) {
                    $data['exceptionType'] = get_class($exception);
                    $data['stackTrace'] = $exception->getTrace();
                }
                $response = static::createJsonResponse($data, $httpCode);
        }

        return $response;
    }

    /**
     * @param mixed $data
     * @param int $httpCode
     * @return JsonResponse
     */
    protected static function createJsonResponse(
        $data,
        int $httpCode
    ): JsonResponse {
        return new JsonResponse($data, $httpCode);
    }
}
