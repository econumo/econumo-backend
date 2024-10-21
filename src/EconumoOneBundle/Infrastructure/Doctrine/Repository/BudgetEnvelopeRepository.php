<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetEnvelope;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;
use App\EconumoOneBundle\Domain\Repository\BudgetEnvelopeRepositoryInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use Throwable;

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

    public function deleteAssociationsWithCategories(Id $budgetId, array $categoriesIds): void
    {
        $conn = $this->getEntityManager()->getConnection();
        $categoriesIdsString = [];
        $placeholders = [];

        foreach ($categoriesIds as $index => $categoryId) {
            $placeholder = ':category_id_' . $index;
            $placeholders[] = $placeholder;
            $categoriesIdsString[$placeholder] = $categoryId->getValue();
        }

        $placeholdersString = implode(', ', $placeholders);

        $sql = <<<SQL
DELETE FROM budgets_envelopes_categories 
WHERE budget_envelope_id IN (
    SELECT id FROM budgets_envelopes WHERE budget_id = :budget_id
) AND category_id IN ($placeholdersString)
SQL;

        try {
            $stmt = $conn->prepare($sql);
            $stmt->executeStatement(array_merge(['budget_id' => $budgetId->getValue()], $categoriesIdsString));
        } catch (Throwable $e) {
            // Handle any database errors
            throw new RuntimeException('Database error: ' . $e->getMessage());
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
