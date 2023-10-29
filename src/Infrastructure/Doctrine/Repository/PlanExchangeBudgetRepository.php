<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Currency;
use App\Domain\Entity\Plan;
use App\Domain\Entity\PlanExchangeBudget;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repository\PlanExchangeBudgetRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * @method PlanExchangeBudget|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanExchangeBudget|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanExchangeBudget[]    findAll()
 * @method PlanExchangeBudget[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanExchangeBudgetRepository extends ServiceEntityRepository implements PlanExchangeBudgetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanExchangeBudget::class);
    }

    public function getNextIdentity(): Id
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $uuid = Uuid::uuid4();

        return new Id($uuid->toString());
    }

    public function get(Id $id): PlanExchangeBudget
    {
        $item = $this->find($id);
        if (!$item instanceof PlanExchangeBudget) {
            throw new NotFoundException(sprintf('PlanExchangeBudget with ID %s not found', $id));
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function save(array $items): void
    {
        try {
            foreach ($items as $item) {
                $this->getEntityManager()->persist($item);
            }

            $this->getEntityManager()->flush();
        } catch (ORMException | ORMInvalidArgumentException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function delete(PlanExchangeBudget $item): void
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    public function getReference(Id $id): PlanExchangeBudget
    {
        return $this->getEntityManager()->getReference(PlanExchangeBudget::class, $id);
    }

    public function getByPlanAndCurrencyId(Id $planId, Id $currencyId, DateTimeInterface $period): array
    {
        return $this->findBy([
            'plan' => $this->getEntityManager()->getReference(Plan::class, $planId),
            'currency' => $this->getEntityManager()->getReference(Currency::class, $currencyId),
            'period' => $period
        ]);
    }
}
