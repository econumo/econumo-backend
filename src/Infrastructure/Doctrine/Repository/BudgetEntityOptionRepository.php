<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\BudgetEntityOption;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetEntityOptionRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetEntityOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetEntityOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetEntityOption[]    findAll()
 * @method BudgetEntityOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetEntityOptionRepository extends ServiceEntityRepository implements BudgetEntityOptionRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetEntityOption::class);
    }

    public function getByBudgetId(Id $budgetId): array
    {
        return $this->findBy(['budget' => $this->getReference($budgetId)]);
    }
}
