<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Doctrine\Repository;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetElement;
use App\EconumoOneBundle\Domain\Entity\Folder;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Exception\NotFoundException;
use App\EconumoOneBundle\Domain\Repository\BudgetElementRepositoryInterface;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\DeleteTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\GetEntityReferenceTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\NextIdentityTrait;
use App\EconumoOneBundle\Infrastructure\Doctrine\Repository\Traits\SaveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetElement[]    findAll()
 * @method BudgetElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetElementRepository extends ServiceEntityRepository implements BudgetElementRepositoryInterface
{
    use NextIdentityTrait;
    use SaveTrait;
    use DeleteTrait;
    use GetEntityReferenceTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetElement::class);
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

    public function getReference(Id $id): BudgetElement
    {
        return $this->getEntityReference(BudgetElement::class, $id);
    }

    public function get(Id $budgetId, Id $externalElementId): BudgetElement
    {
        $item = $this->findOneBy(
            [
                'budget' => $this->getEntityReference(Budget::class, $budgetId),
                'externalId' => $externalElementId
            ]
        );
        if (!$item instanceof BudgetElement) {
            throw new NotFoundException(
                sprintf(
                    'BudgetElementOption with ID %s not found',
                    $externalElementId->getValue()
                )
            );
        }

        return $item;
    }

    public function getNextPosition(Id $budgetId, ?Id $folderId): int
    {
        $builder = $this->createQueryBuilder('e');
        $builder
            ->select('e')
            ->where('e.budget = :budget')
            ->setParameter('budget', $this->getEntityReference(Budget::class, $budgetId))
            ->orderBy('e.position', 'DESC')
            ->setMaxResults(1);
        if ($folderId) {
            $builder
                ->andWhere('e.folder = :folder')
                ->setParameter('folder', $this->getEntityReference(Folder::class, $folderId));
        }
        try {
            $element = $builder->getQuery()->getSingleResult();
            $position = $element->getPosition() + 1;
        } catch (NoResultException $e) {
            $position = 0;
        }

        return $position;
    }

    public function getElementsByExternalId(Id $externalElementId): array
    {
        return $this->findBy(['externalId' => $externalElementId]);
    }
}
