<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetAccess;
use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;
use App\EconumoOneBundle\Domain\Repository\BudgetAccessRepositoryInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetAccess[]    findAll()
 * @method BudgetAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetAccessRepository extends ServiceEntityRepository implements BudgetAccessRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetAccess::class);
    }

    public function getReference(Id $id): BudgetAccess
    {
        return $this->getEntityManager()->getReference(BudgetAccess::class, $id);
    }

    public function getByBudgetId(Id $budgetId): array
    {
        return $this->findBy(['budget' => $this->getEntityReference(Budget::class, $budgetId)]);
    }

    public function get(Id $budgetId, Id $userId): BudgetAccess
    {
        $item = $this->findOneBy([
            'budget' => $this->getReference($budgetId),
            'user' => $this->getEntityManager()->getReference(User::class, $userId)
        ]);
        if (!$item instanceof BudgetAccess) {
            throw new NotFoundException(sprintf('BudgetAccess with ID %s not found', $budgetId));
        }

        return $item;
    }

    public function getPendingAccess(Id $userId): array
    {
        return $this->findBy([
            'user' => $this->getEntityManager()->getReference(User::class, $userId),
            'isAccepted' => false
        ]);
    }

    public function getByUser(Id $userId): array
    {
        return $this->findBy([
            'user' => $this->getEntityManager()->getReference(User::class, $userId)
        ]);
    }
}
