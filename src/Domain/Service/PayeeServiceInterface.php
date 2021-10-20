<?php

declare(strict_types=1);


namespace App\Domain\Service;

use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;

interface PayeeServiceInterface
{
    public function createPayee(Id $userId, Id $payeeId, string $name): Payee;

    public function createPayeeForAccount(Id $userId, Id $accountId, Id $payeeId, string $name): Payee;
}
