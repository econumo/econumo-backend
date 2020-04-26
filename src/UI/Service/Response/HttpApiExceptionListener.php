<?php

declare(strict_types=1);

namespace App\UI\Service\Response;

use App\Application\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpApiExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();
        if ($event->getRequest() instanceof Request) {
            if ($exception instanceof ValidationException) {
                $response = ResponseFactory::createErrorResponse(
                    $event->getRequest(),
                    $exception->getMessage(),
                    $exception->getErrors()
                );
            } elseif ($exception instanceof HttpException) {
                $response = ResponseFactory::createErrorResponse(
                    $event->getRequest(),
                    $exception->getMessage(),
                    [],
                    $exception->getStatusCode()
                );
            } else {
                throw $exception;
            }
        } else {
            throw $exception;
        }

        $event->setResponse($response);
    }
}
