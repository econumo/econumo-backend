<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class AuthenticationUpdateTokenPayload
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onTokenCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }

        $data = $event->getData();
        $data['id'] = $user->getId()->getValue();
        $data['name'] = $user->getName();
        $data['roles'] = $user->getRoles();
        $data['avatar'] = $user->getAvatarUrl();

        $event->setData($data);
    }
}
