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
        $data['name'] = $user->getName();
        $data['roles'] = $user->getRoles();
        $data['avatar'] = $user->getAvatarUrl();
        $data['server'] = [
            'base_currency' => $this->baseCurrency
        ];
        $data['options'] = [];
        foreach ($user->getOptions() as $option) {
            $data['options'][$option->getName()] = $option->getValue();
        }

        if (empty($data['options'][UserOption::CURRENCY])) {
            $data['options'][UserOption::CURRENCY] = UserOption::DEFAULT_CURRENCY;
        }

        if (empty($data['options'][UserOption::REPORT_DAY])) {
            $data['options'][UserOption::REPORT_DAY] = UserOption::DEFAULT_REPORT_DAY;
        }

        $event->setData($data);
    }
}
