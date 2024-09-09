<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\BudgetEnvelope;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
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

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetEnvelope::class);
    }

    public function getByBudgetId(Id $budgetId): array
    {
        return $this->findBy(['budget' => $this->getReference($budgetId)]);
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
