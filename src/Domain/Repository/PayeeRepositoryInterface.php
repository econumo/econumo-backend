<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;

interface PayeeRepositoryInterface
{
    public function getNextIdentity(): Id;

    /**
     * @param Id $userId
     * @return Payee[]
     */
    public function findByUserId(Id $userId): array;

    /**
     * @param Id $userId
     * @return Payee[]
     */
    public function findByOwnerId(Id $userId): array;

    public function get(Id $id): Payee;

    public function save(Payee ...$payees): void;

    public function getReference(Id $id): Payee;

    public function delete(Payee $payee): void;
}
