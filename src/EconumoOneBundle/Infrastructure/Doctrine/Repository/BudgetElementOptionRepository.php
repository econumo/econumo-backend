<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetElementOption;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Repository\BudgetElementOptionRepositoryInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetElementOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetElementOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetElementOption[]    findAll()
 * @method BudgetElementOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetElementOptionRepository extends ServiceEntityRepository implements BudgetElementOptionRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetElementOption::class);
    }

    public function getByBudgetId(Id $budgetId): array
    {
        return $this->findBy(
            [
                'budget' => $this->getEntityManager()->getReference(Budget::class, $budgetId)
            ],
            ['position' => 'ASC']
        );
    }

    public function getReference(Id $id): BudgetElementOption
    {
        return $this->getEntityReference(BudgetElementOption::class, $id);
    }
}
