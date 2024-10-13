<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Auth;

use App\EconumoOneBundle\Application\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new ValidationException($exception->getMessage(), $exception->getCode(), $exception);
    }
}