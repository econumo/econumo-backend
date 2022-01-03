<?php

declare(strict_types=1);


namespace App\Domain\Service;

use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;

interface PayeeServiceInterface
{
    public function createPayee(Id $userId, string $name): Payee;

    public function createPayeeForAccount(Id $userId, Id $accountId, string $name): Payee;

    public function updatePayee(Id $payeeId, string $name, bool $isArchived): void;

    public function deletePayee(Id $payeeId): void;
}
