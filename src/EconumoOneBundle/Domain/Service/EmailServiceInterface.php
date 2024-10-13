<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service;


use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface EmailServiceInterface
{
    public function sendResetPasswordConfirmationCode(Email $recipient, Id $userId): void;
}
