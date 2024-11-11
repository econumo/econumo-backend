<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service;


use App\EconumoBundle\Domain\Entity\ValueObject\Email;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;

interface EmailServiceInterface
{
    public function sendResetPasswordConfirmationCode(Email $recipient, Id $userId): void;
}
