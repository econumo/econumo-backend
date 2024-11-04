<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Repository;

use App\EconumoOneBundle\Domain\Entity\Payee;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;

interface PayeeRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @return Payee[]
     */
    public function findAvailableForUserId(Id $userId): array;

    /**
     * @return Payee[]
     */
    public function findByOwnerId(Id $userId): array;

    public function get(Id $id): Payee;

    /**
     * @param Payee[] $payees
     */
    public function save(array $payees): void;

    public function getReference(Id $id): Payee;

    public function delete(Payee $payee): void;
}
