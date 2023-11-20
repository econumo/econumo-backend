<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;

interface EmailServiceInterface
{
    public function sendResetPasswordConfirmationCode(Email $recipient, Id $userId): void;
}
