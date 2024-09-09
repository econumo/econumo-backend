<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\BudgetFolder;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetFolderRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
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
        return $this->findBy(['budget' => $this->getReference($budgetId)]);
    }
}
