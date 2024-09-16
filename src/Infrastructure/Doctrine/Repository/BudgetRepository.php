<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Budget;
use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\BudgetAccessRepositoryInterface;
use App\Domain\Repository\BudgetRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Budget|null find($id, $lockMode = null, $lockVersion = null)
 * @method Budget|null findOneBy(array $criteria, array $orderBy = null)
 * @method Budget[]    findAll()
 * @method Budget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetRepository extends ServiceEntityRepository implements BudgetRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(
        private BudgetAccessRepositoryInterface $budgetAccessRepository,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Budget::class);
    }

    public function get(Id $id): Budget
    {
        $item = $this->find($id);
        if (!$item instanceof Budget) {
            throw new NotFoundException(sprintf('Budget with ID %s not found', $id));
        }

        return $item;
    }

    public function getReference(Id $id): Budget
    {
        return $this->getEntityReference(Budget::class, $id);
    }

    public function getByUserId(Id $userId): array
    {
        $budgets = $this->findBy([
            'user' => $this->getEntityReference(User::class, $userId)
        ]);

        $accessList = $this->budgetAccessRepository->getByUser($userId);
        foreach ($accessList as $access) {
            $budgets[] = $access->getBudget();
        }

        return $budgets;
    }
}
