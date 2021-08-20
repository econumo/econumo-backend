<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;

interface PayeeRepositoryInterface
{
    /**
     * @param Id $id
     * @return Payee[]
     */
    public function findByUserId(Id $id): array;
}
