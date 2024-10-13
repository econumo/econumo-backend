<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\Payee;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\PayeeName;
use App\EconumoOneBundle\Domain\Service\Dto\PositionDto;
use DateTimeInterface;

interface PayeeServiceInterface
{
    public function createPayee(Id $userId, PayeeName $name): Payee;

    public function createPayeeForAccount(Id $userId, Id $accountId, PayeeName $name): Payee;

    public function updatePayee(Id $payeeId, PayeeName $name): void;

    public function deletePayee(Id $payeeId): void;

    /**
     * @param Id $userId
     * @param PositionDto[] $changes
     * @return void
     */
    public function orderPayees(Id $userId, array $changes): void;

    public function archivePayee(Id $payeeId): void;

    public function unarchivePayee(Id $payeeId): void;
}
