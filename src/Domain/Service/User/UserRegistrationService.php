<?php

declare(strict_types=1);


namespace App\Domain\Service\User;


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
