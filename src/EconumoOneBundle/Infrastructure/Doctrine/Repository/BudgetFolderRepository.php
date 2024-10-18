<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetFolder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;
use App\EconumoOneBundle\Domain\Repository\BudgetFolderRepositoryInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
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
    use GetEntityReferenceTrait;

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
        return $this->findBy(
            [
                'budget' => $this->getEntityReference(Budget::class, $budgetId)
            ],
            [
                'position' => 'ASC'
            ]
        );
    }

    public function get(Id $id): BudgetFolder
    {
        $item = $this->find($id);
        if (!$item instanceof BudgetFolder) {
            throw new NotFoundException(sprintf('BudgetFolder with ID %s not found', $id));
        }

        return $item;
    }
}
