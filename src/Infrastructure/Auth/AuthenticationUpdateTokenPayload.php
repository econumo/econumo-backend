<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Domain\Entity\User;
use App\Domain\Entity\UserOption;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class AuthenticationUpdateTokenPayload
{
    public function __construct(private readonly string $baseCurrency)
    {
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onTokenCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }

        $data = $event->getData();
        $data['id'] = $user->getId()->getValue();

        $event->setData($data);
    }
}
