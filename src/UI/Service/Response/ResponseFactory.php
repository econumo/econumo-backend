<?php

declare(strict_types=1);

namespace App\UI\Service\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    /**
     * @param Request $request
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return Response
     */
    public static function createOkResponse(
        Request $request,
        $data = [],
        string $message = '',
        int $code = Response::HTTP_OK
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $response = static::createJsonResponse([
                    'message' => $message,
                    'data' => $data,
                ], $code);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param string $message
     * @param array $errors
     * @param int $code
     * @return Response
     */
    public static function createErrorResponse(
        Request $request,
        string $message = '',
        array $errors = [],
        int $code = Response::HTTP_BAD_REQUEST
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $response = static::createJsonResponse([
                    'message' => $message,
                    'errors' => $errors,
                ], $code);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param string $message
     * @param string $exceptionMessage
     * @param \Throwable $exception
     * @param int $code
     * @return Response
     */
    public static function createExceptionResponse(
        Request $request,
        string $message,
        string $exceptionMessage,
        ?\Throwable $exception = null,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): Response {
        $formats = $request->headers->get('content-type');

        switch ($formats) {
            default:
                $data = [
                    'message' => $message,
                    'exceptionMessage' => $exceptionMessage,
                ];
                if (null !== $exception) {
                    $data['exceptionType'] = get_class($exception);
                    $data['stackTrace'] = $exception->getTrace();
                }
                $response = static::createJsonResponse($data, $code);
        }

        return $response;
    }

    /**
     * @param mixed $data
     * @param int $code
     * @return JsonResponse
     */
    protected static function createJsonResponse(
        $data,
        int $code
    ): JsonResponse {
        return new JsonResponse($data, $code);
    }
}
