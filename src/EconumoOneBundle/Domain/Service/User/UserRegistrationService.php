<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service\User;


use App\EconumoOneBundle\Domain\Service\User\UserRegistrationServiceInterface;

readonly class UserRegistrationService implements UserRegistrationServiceInterface
{
    public function __construct(private bool $isRegistrationAllowed)
    {
    }

    public function isRegistrationAllowed(): bool
    {
        return $this->isRegistrationAllowed;
    }
}