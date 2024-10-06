<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Repository;

use App\EconumoBundle\Domain\Entity\Budget;
use App\EconumoBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Exception\NotFoundException;
use App\EconumoBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetEnvelope|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetEnvelope|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetEnvelope[]    findAll()
 * @method BudgetEnvelope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetEnvelopeRepository extends ServiceEntityRepository implements BudgetEnvelopeRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetEnvelope::class);
    }

    public function getByBudgetId(Id $budgetId, bool $onlyActive = null): array
    {
        if ($onlyActive === null) {
            return $this->findBy([
                'budget' => $this->getEntityReference(Budget::class, $budgetId)
            ]);
        } else {
            return $this->findBy([
                'budget' => $this->getEntityReference(Budget::class, $budgetId),
                'isArchived' => !!$onlyActive
            ]);
        }
    }

    public function get(Id $id): BudgetEnvelope
    {
        $item = $this->find($id);
        if (!$item instanceof BudgetEnvelope) {
            throw new NotFoundException(sprintf('BudgetEnvelope with ID %s not found', $id));
        }

        return $item;
    }

    public function getReference(Id $id): BudgetEnvelope
    {
        return $this->getEntityManager()->getReference(BudgetEnvelope::class, $id);
    }
}
