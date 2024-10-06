<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Repository;

use App\EconumoBundle\Domain\Entity\Budget;
use App\EconumoBundle\Domain\Entity\BudgetFolder;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetFolder|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetFolder|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetFolder[]    findAll()
 * @method BudgetFolder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetFolderRepository extends ServiceEntityRepository implements BudgetFolderRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetFolder::class);
    }

    public function getReference(Id $id): BudgetFolder
    {
        return $this->getEntityManager()->getReference(BudgetFolder::class, $id);
    }

    public function getByBudgetId(Id $budgetId): array
    {
        return $this->findBy(['budget' => $this->getEntityManager()->getReference(Budget::class, $budgetId)]);
    }
}
