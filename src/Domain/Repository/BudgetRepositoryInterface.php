<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Budget;
use App\Domain\Entity\ValueObject\Id;

/**
 * @method Budget|null find($id, $lockMode = null, $lockVersion = null)
 * @method Budget|null findOneBy(array $criteria, array $orderBy = null)
 * @method Budget[]    findAll()
 * @method Budget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface BudgetRepositoryInterface
{
    /**
     * @param Id $id
     * @return Budget[]
     */
    public function findByUserId(Id $id): array;
}
