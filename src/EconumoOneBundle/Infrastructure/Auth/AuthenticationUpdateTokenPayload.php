<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Auth;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\UserOption;
use App\EconumoOneBundle\Domain\Service\EncodeServiceInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

readonly class AuthenticationUpdateTokenPayload
{
    public function __construct(
        private readonly string $baseCurrency,
        private EncodeServiceInterface $encodeService,
    ) {
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
        $data['username'] = $this->encodeService->decode($user->getEmail());

        $event->setData($data);
    }
}
