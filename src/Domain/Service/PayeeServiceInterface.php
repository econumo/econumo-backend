<?php

declare(strict_types=1);


namespace App\Domain\Service;

use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\Dto\PositionDto;

interface PayeeServiceInterface
{
    public function createPayee(Id $userId, string $name): Payee;

    public function createPayeeForAccount(Id $userId, Id $accountId, string $name): Payee;

    public function updatePayee(Id $payeeId, string $name): void;

    public function deletePayee(Id $payeeId): void;

    public function orderPayees(Id $userId, PositionDto ...$changes): void;

    public function archivePayee(Id $payeeId): void;

    public function unarchivePayee(Id $payeeId): void;
}
