<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;

interface BudgetRepositoryInterface
{
    /**
     * @param Id $id
     * @return Budget[]
     */
    public function findByUserId(Id $id): array;
}
