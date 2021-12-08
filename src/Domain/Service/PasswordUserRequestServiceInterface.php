<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\ValueObject\Email;

interface PasswordUserRequestServiceInterface
{
    public function remindPassword(Email $email): void;
}
